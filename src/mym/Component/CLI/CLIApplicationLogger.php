<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Component\CLI;

use mym\Component\CLI\CLIApplication;
use Psr\Log\LogLevel;
use Psr\Log\AbstractLogger;

/**
 * Logger adapter for CLIApplication
 */
class CLIApplicationLogger extends AbstractLogger
{
  /**
   * @var CLIApplication
   */
  private $cliApplication;

  public function __construct(CLIApplication $cliApplication)
  {
    $this->cliApplication = $cliApplication;
  }

  public function log($level, $message, array $context = array())
  {
    switch ($level) {
      case LogLevel::ALERT:
        $this->cliApplication->status($this->interpolate('ALERT: ' . $message, $context));
        break;

      case LogLevel::CRITICAL:
        $this->cliApplication->error($this->interpolate('CRITICAL: ' . $message, $context));
        break;

      case LogLevel::DEBUG:
        $this->cliApplication->debug($this->interpolate('DEBUG: ' . $message, $context));
        break;

      case LogLevel::EMERGENCY:
        $this->cliApplication->error($this->interpolate('EMERGENCY: ' . $message, $context));
        break;

      case LogLevel::ERROR:
        $this->cliApplication->error($this->interpolate('ERROR: ' . $message, $context));
        break;

      case LogLevel::INFO:
        $this->cliApplication->info($this->interpolate('INFO: ' . $message, $context));
        break;

      case LogLevel::NOTICE:
        $this->cliApplication->info($this->interpolate('NOTICE: ' . $message, $context));
        break;

      case LogLevel::WARNING:
        $this->cliApplication->error($this->interpolate('WARNING: ' . $message, $context));
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