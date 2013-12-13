<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Component\CLI;

use mym\Component\CLI\CLICommand;

class Console
{
  protected $projectName;
  protected $logDir;

  public function run($argc, $argv)
  {
    if ($argc == 1 || $argv[1] == "?") {

      die("Usage: {$argv[0]} <command>|list <parameters>\n");

    } else if ($argv[1] == "list") {

      $g = glob("../modules/{$this->projectName}/Command/*.php");

      foreach ($g as $f) {
        $f = basename($f);
        $f = str_replace(".php", "", $f);
        echo "$f\n";
      }

      exit(0);
    }

    try {

      // create
      $command = "{$this->projectName}\Command\\" . preg_replace('#[/\\\\]+#s', '\\', $argv[1]);
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

  public function getProjectName()
  {
    return $this->projectName;
  }

  public function setProjectName($projectName)
  {
    $this->projectName = $projectName;
  }

  public function getLogDir()
  {
    return $this->logDir;
  }

  public function setLogDir($logDir)
  {
    $this->logDir = $logDir;
  }
}