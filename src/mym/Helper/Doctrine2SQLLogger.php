<?php

/**
 * Doctrine 2 ORM helper service
 * Requires DOctrine 2.0 library, downloaded from
 * www.doctrine-project.org
 *
 * @uses Doctrine ORM 2.0
 * @copyright 2011 Misha Yurasov
 * @package mym
 */

namespace mym\Helper;

class Doctrine2SQLLogger implements \Doctrine\DBAL\Logging\SQLLogger
{
  private $queryTime = 0.0;
  private $logFile = '';
  private $fp;

  public function __construct()
  {
    $this->logFile = Config::$options['Doctrine2SQLLogger']['logFile'];

    if (!file_exists($this->logFile))
      touch($this->logFile);

    @chmod($this->logFile, 0666);
    $this->fp = fopen($this->logFile, 'a');
  }

  public function startQuery($sql, array $params = null, array $types = null)
  {
    $this->_log($sql);
    $this->queryTime = microtime(true);
  }

  public function stopQuery()
  {
    $this->_log(round(microtime(true) - $this->queryTime, 6) * 1000 .  "ms \n");
  }

  private function _log($text)
  {
    fwrite($this->fp, $text . "\n");
  }

  public function __destruct()
  {
    fclose($this->fp);
  }
}