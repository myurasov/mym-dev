<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Component\CLI;

use mym\Component\CLI\CLICommand;
use Symfony\Component\Finder\Finder;

class Console
{
  private $logDir;
  private $commandDir;
  private $commandNamespace;

  public function run($argc, $argv)
  {
    if ($argc == 1 || $argv[1] == "?") {

      die("Usage: {$argv[0]} <command>|list <parameters>\n");

    } else if ($argv[1] == "list") {

      $finder = new Finder();

      foreach ($finder->files()->in($this->getCommandDir()) as $file) {
        echo $this->getClass($file, false), "\n";
      }

      exit(0);
    }

    try {

      // create
      $command = $this->commandNamespace . '\\' . preg_replace('#[/\\\\]+#s', '\\', $argv[1]);
      $command /* @var $command CLICommand */ = new $command;

      // set log file
      $command->getCa()->options->set(
        "log_file",
        $this->logDir . "/" . $argv[1] . ".log"
      );

      // run
      $command->run();

    } catch (\Exception $e) {

      echo "Exception occured: " .  $e->getMessage(), "\n";

    }

  }

  private function getClass($file, $namespaced = true)
  {
    $class = substr($file, strlen($this->getCommandDir()) + 1);
    $class = preg_replace('#\.php$#i', '', $class);
    $class = str_replace('/', '\\', $class);

    if ($namespaced) {
      $class = $this->commandNamespace . '\\' . $class;
    }

    return $class;
  }

  // <editor-fold defaultstate="collapsed" desc="accessors">

  public function getLogDir()
  {
    return $this->logDir;
  }

  public function setLogDir($logDir)
  {
    $this->logDir = $logDir;
  }

  public function getCommandDir()
  {
    return realpath($this->commandDir);
  }

  public function setCommandDir($commandDir)
  {
    $this->commandDir = $commandDir;
  }

  public function getCommandNamespace()
  {
    return $this->commandNamespace;
  }

  public function setCommandNamespace($commandNamespace)
  {
    $this->commandNamespace = $commandNamespace;
  }

  // </editor-fold>
}
