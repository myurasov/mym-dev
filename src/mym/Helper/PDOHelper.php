<?php

/**
 * PDO database helper
 *
 * Requires PDO php extension
 *
 * @copyright 2010 Misha Yurasov
 * @package ymF
 */

namespace ymF\Helper;

use PDO;
use ymF\Helper\Config;
use ymF\Exception\Exception;

class PDOHelper
{
  /**
   *
   * @var PDO
   */
  private static $pdo;

  // Config array
  private static $config;

  /**
   * Returns PDO instance
   *
   * @param array|null $config
   * @return PDO
   */
  public static function getPDO($config = null)
  {
    if (is_null(self::$pdo))
      self::createPDO($config);

    return self::$pdo;
  }

  /**
   * Creates PDo instance
   *
   * @param array|null $config
   * @return bool
   */
  public static function createPDO($config = null)
  {
    if (is_null(self::$pdo))
    {
      if (!is_null($config))
        self::setConfig($config);
      else if (is_null(self::$config))
        self::setConfig(Config::$options['PDOHelper']);

      self::$pdo = new PDO(
        self::$config['dsn'],
        self::$config['user'],
        self::$config['password'],
        self::$config['options']);

      // Throw exception on errors
      self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Associative array fetching
      self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      return true;
    }
    else
    {
      throw new Exception('PDO instance already created', \ymF\ERROR_MISC);
    }
  }

  /**
   * Prepare SQL statement for execution
   * - Replace %k=v, %k, %v sequences
   * - Replace %s by calling sprintf
   *
   * %k=v     -->   (key=value, key2=value2,..)
   * %k, %v   -->   (key, key2,...), (value, value2,..)
   *
   * @param string $sql
   * @param mixed $values
   * @return string
   */
  public static function prepareSQL()
  {
    // PDO instance should be created

    if (is_null(self::$pdo))
    {
      throw new Exception('PDO instance is not yet created', \ymF\ERROR_MISC);
    }

    $arguments = func_get_args();
    $array_counter = 0;
    $arguments_count = count($arguments);

    for ($i = 1; $i < $arguments_count; $i++)
    {
      if (is_array($arguments[$i]))
      {
        $array_counter++;

        // %k=v (key=value, key2=value2,..)
        // %k, %v (key, key2,...), (value, value2,..)

        $pattern_kv = '/%k=v' . $array_counter . ($array_counter == 1 ? '?' : '') . '\b/m';
        $pattern_k  = '/%k' . $array_counter . ($array_counter == 1 ? '?' : '') . '\b/m';
        $pattern_v  = '/%v' . $array_counter . ($array_counter == 1 ? '?' : '') . '\b/m';

        $keys_values = $keys = $values = array();

        if (count($arguments[$i]) > 0)
        {
          foreach ($arguments[$i] as $k => $v)
          {
            // $k = self::qoute($k);
            $v = self::qoute($v);
            $keys_values[] = $k . '=' . $v;
            $keys[] = $k;
            $values[] = $v;
          }

          $keys_values = implode(',', $keys_values);
          $keys = implode(',', $keys);
          $values = implode(',', $values);
        }
        else
        {
          $keys_values = '';
          $keys = '';
          $values = '';
        }

        $arguments[0] = preg_replace(array($pattern_kv, $pattern_k, $pattern_v),
          array($keys_values, $keys, $values), $arguments[0]);

        unset($arguments[$i]);
      }
      else
      {
        $arguments[$i] = self::qoute($arguments[$i]);
      }
    }

    if (count($arguments) > 1)
    {
      return call_user_func_array('sprintf', $arguments);
    }
    else
    {
      return $arguments[0];
    }
  }

  /**
   * Quote variable for usage in SQL statement
   * @param mixed $var
   * @return string
   */
  public static function qoute($var)
  {
    // PDO instance should be created first

    if (is_null(self::$pdo))
    {
      throw new Exception('PDO instance is not yet created', \ymF\ERROR_MISC);
    }

    // Convert objects to string
    if (is_object($var))
      $var = (string) $var;

    if (is_bool($var))
    {
      return $var ? 'TRUE' : 'FALSE';
    }
    elseif (is_null($var))
    {
      return 'NULL';
    }
    else
    {
      return self::$pdo->quote($var);
    }
  }

  /**
   * Delete PDO instance
   */
  public static function reset()
  {
    self::$pdo = null;
  }

  /**
   * Set config
   * @param array $config
   */
  public static function setConfig($config)
  {
    self::$config = $config;
  }

  /**
   * Prevent creation of class instance
   */
  final private function __construct() {}
}