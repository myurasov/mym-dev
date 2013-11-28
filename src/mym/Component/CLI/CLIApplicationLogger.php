<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Component\CLI;

use mym\Component\CLI\CLIApplication;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Logger adapter for CLIApplication
 */
class CLIApplicationLogger implements LoggerInterface
{
  /**
   * @var CLIApplication
   */
  private $cliApplication;

  public function __construct(CLIApplication $cliApplication)
  {
    $this->cliApplication = $cliApplication;
  }

  public function alert($message, array $context = array())
  {
    $this->cliApplication->status($this->interpolate($message, $context));
  }

  public function critical($message, array $context = array())
  {
    $this->cliApplication->error($this->interpolate($message, $context));
  }

  public function debug($message, array $context = array())
  {
    $this->cliApplication->debug($this->interpolate($message, $context));
  }

  public function emergency($message, array $context = array())
  {
    $this->cliApplication->error($this->interpolate($message, $context));
  }

  public function error($message, array $context = array())
  {
    $this->cliApplication->error($this->interpolate($message, $context));
  }

  public function info($message, array $context = array())
  {
    $this->cliApplication->info($this->interpolate($message, $context));
  }

  public function notice($message, array $context = array())
  {
    $this->cliApplication->info($this->interpolate($message, $context));
  }

  public function warning($message, array $context = array())
  {
    $this->cliApplication->error($this->interpolate($message, $context));
  }

  public function log($level, $message, array $context = array())
  {
    switch ($level) {
      case LogLevel::ALERT:
        $this->alert($message, $context);
        break;

      case LogLevel::CRITICAL:
        $this->critical($message, $context);
        break;

      case LogLevel::DEBUG:
        $this->debug($message, $context);
        break;

      case LogLevel::EMERGENCY:
        $this->emergency($message, $context);
        break;

      case LogLevel::ERROR:
        $this->error($message, $context);
        break;

      case LogLevel::INFO:
        $this->info($message, $context);
        break;

      case LogLevel::NOTICE:
        $this->notice($message, $context);
        break;

      case LogLevel::WARNING:
        $this->warning($message, $context);
        break;

      default:
        $this->info($message, $context);
        break;
    }
  }

  /**
   * Interpolates context values into the message placeholders.
   */
  private function interpolate($message, array $context = array())
  {
    // build a replacement array with braces around the context keys
    $replace = array();
    foreach ($context as $key => $val) {
      $replace['{' . $key . '}'] = $val;
    }

    // interpolate replacement values into the message and return
    return strtr($message, $replace);
  }

  // <editor-fold defaultstate="collapsed" desc="accessors">

  public function getCliApplication()
  {
    return $this->cliApplication;
  }

  public function setCliApplication(CLIApplication $cliApplication)
  {
    $this->cliApplication = $cliApplication;
  }

  // </editor-fold>
}