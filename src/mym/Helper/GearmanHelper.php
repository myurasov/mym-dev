<?php

namespace ymF\Helper;

class GearmanHelper
{
  static $config = null;

  public static function setConfig($config = null)
  {
    static::$config = is_null($config)
      ? Config::$options['GearmanHelper']
      : $config;
  }

  /**
   * Get GeramanClient instance
   *
   * @param array $config
   * @return \GearmanClient
   */
  public static function getClient($config = null)
  {
    self::setConfig($config);

    $client = new \GearmanClient();

    foreach ($config['servers'] as $server)
      $client->addServer($server['host'], $server['port']);

    return $client;
  }

  /**
   * Get GeramanWorker instance
   *
   * @param array $config
   * @return \GearmanWorker
   */
  public static function getWorker($config = null)
  {
    self::setConfig($config);

    $worker = new \GearmanWorker();

    foreach ($config['servers'] as $server)
      $worker->addServer($server['host'], $server['port']);

    return $worker;
  }

  /**
   * Encode data for passing in Gearman functions
   *
   * @param mixed $workload
   * @return string
   */
  public static function encodeData($workload)
  {
    return gzdeflate(serialize($workload), 9);
  }

  /**
   * Decode data
   *
   * @param string $workload
   * @return mixed
   */
  public static function decodeData($workload)
  {
    return unserialize(gzinflate($workload));
  }

  /**
   * Create full function name
   *
   * @param string $name
   * @param string $hostname
   */
  public static function createFunction($name, $hostname = null)
  {
    return \ymF\PROJECT_NAME . ':' .
      $name . (is_null($hostname) ? '' : "@$hostname");
  }
}