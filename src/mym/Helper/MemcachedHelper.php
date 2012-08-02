<?php

namespace ymF\Helper;

use ymF\Helper\Config;

class MemcachedHelper
{
  static $config;
  static $memcached;

  public static function setConfig($config = null)
  {
    if (is_null($config))
      $config = Config::$options['MemcachedHelper'];
    self::$config = $config;
  }

  /**
   * Get Memcached instance
   *
   * @param array $config
   * @return Memcached
   */
  public static function getMemcached($config = null)
  {
    if (is_null(static::$memcached))
    {
      self::setConfig($config);

      self::$memcached = new \Memcached(self::$config['persistentId']);

      if (is_null(self::$config['persistentId'])
        || count(self::$memcached->getServerList()) == 0)
          self::$memcached->addServers(self::$config['servers']);

      foreach (self::$config['options'] as $option => $value)
        self::$memcached->setOption($option, $value);
    }

    return self::$memcached;
  }
}