<?php

/**
 * ym Framework main file
 *
 * @copyright Misha Yurasov 2009-2011
 * @package ymF
 */

namespace ymF;

use ymF\Exception\Exception;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use ymF\Exception\NotFoundException;

/**
 * ymF Kernel class
 *
 */
class Kernel
{
  // Root namespaces registered for autoloading
  private static $autoload = array();

  //  Config
  private static $conf = null;

  /**
   * Handle HTTP request
   */
  public static function handleHttpRequest()
  {
    try
    {
      // create request
      $request = Request::createFromGlobals();

      // Route

      $httpRouter = Config::$options['httpRouter'];
      $httpRouter = new $httpRouter;

      $httpRouter->route($request);
      $controller = $httpRouter->getController();
      $action = $httpRouter->getAction();

      // Call controller

      $controller = new $controller;
      $response = $controller->$action($request);

      if (!($response instanceof Response))
        throw new Exception("Response object should be returned");
    }
    catch (NotFoundException $e)
    {
      $response = new Response($e->getMessage(), 404);
    }
    catch (\Exception $e)
    {
      $response = new Response($e->getMessage(), 500);
    }

    // send response
    $response->send();
  }

  /**
   * Initialize ymF
   *
   */
  public static function selfInit()
  {
    // Define version

    // major.minor<.change>< status>
    define('ymF\VERSION', '0.8-dev');

    if (!defined('ymF\DEVELOPMENT'))
      define('ymF\DEVELOPMENT', false);

    // relocate ymF config?
    if (!defined('ymF\RELOC_CONFIG'))
      define ('ymF\RELOC_CONFIG', true);

    // Define paths:

    // Project root directory

    if (!defined('ymF\PATH_ROOT'))
      define('ymF\PATH_ROOT', realpath(__DIR__ . '/../../..'));

    // Core executable files
    if (!defined('ymF\PATH_SRC'))
      define('ymF\PATH_SRC', PATH_ROOT . '/src');

    // Web documents
    if (!defined('ymF\PATH_WWW'))
      define('ymF\PATH_WWW', PATH_SRC . '/www');

    // Command-line interface
    if (!defined('ymF\PATH_CLI'))
      define('ymF\PATH_CLI', PATH_SRC . '/cli');

    // Variable application data
    if (!defined('ymF\PATH_DATA'))
      define('ymF\PATH_DATA', PATH_ROOT . '/data');

    // Temporary data
    if (!defined('ymF\PATH_TEMP'))
      define('ymF\PATH_TEMP', PATH_DATA . '/temp');

    // Code modules and root namespace
    if (!defined('ymF\PATH_MODULES'))
      define('ymF\PATH_MODULES', PATH_SRC . '/modules');

    // Templates
    if (!defined('ymF\PATH_TEMPLATES'))
      define('ymF\PATH_TEMPLATES', PATH_SRC . '/templates');

    // Resource files
    if (!defined('ymF\PATH_RESOURCES'))
      define('ymF\PATH_RESOURCES', PATH_SRC . '/resources');

    // Bundled libraries
    if (!defined('ymF\PATH_LIBRARIES'))
      define('ymF\PATH_LIBRARIES', PATH_SRC . '/libraries');

    // Config classes
    if (!defined('ymF\PATH_CONFIGURATION'))
      define('ymF\PATH_CONFIGURATION', PATH_SRC . '/configs');

    // Logs
    if (!defined('ymF\PATH_LOGS'))
      define('ymF\PATH_LOGS', PATH_DATA . '/logs');

    // Define errors:

    define('ymF\ERROR_OK', 0);
    define('ymF\ERROR_MISC', -1);

    // Register autoloader function
    spl_autoload_register(__CLASS__ . '::autoload');

    // Register ymF autoloading
    self::registerAutoloadNamespace('ymF', __DIR__, RELOC_CONFIG);
  }

  /**
   * Post-load initialisation
   */
  public static function init()
  {
    self::registerAutoloadNamespace('Symfony\Component\HttpFoundation',
      self::getLibraryPath('SymfonyComponents_HttpFoundation'));
  }

  /**
   * Get library path
   *
   * @param string $library
   * @return string
   */
  public static function getLibraryPath($library)
  {
    return Config::$options['libraries'][$library];
  }

  /**
   * Register namespace for autoloading
   *
   * @param <type> $namespace
   * @param <type> $root
   * @param <type> $relocate_config Search configs in ymF\PATH_CONFIGURATION as namespace.subnamespace.Config.php
   */
  public static function registerAutoloadNamespace(
    $namespace, $root = null, $relocate_config = false)
  {
    self::$autoload[$namespace] = array(
      'root'          => $root,
      'config_reloc'  => $relocate_config
    );
  }

  /**
   * Loads required class on first usage
   *
   * Something\Another\Config - from core\configs\Something.Another.Config.php
   * Something\Another\Class - from core\modules\Something\Another\Class.php
   *
   * @param string $className
   */
  public static function autoload($className)
  {
    // Load only registered namespaces

    $registered = false;

    foreach (self::$autoload as $namespace => $options)
    {
      if (substr($className, 0, strlen($namespace)) == $namespace)
      {
        $registered = true;
        break;
      }
    }

    if ($registered)
    {
      // Check class name for double slashes
      // (Valid in file path, but invalid in classs names)

      if (preg_match('#[\\\\\\/][\\\\\\/]#', $className))
        throw new Exception("Invalid class name '$className'", ERROR_MISC);

      if ($options['config_reloc'] && ($className === 'Config' || substr($className, -7) == '\\Config'))
      {
        // Load configs from
        // Kernel\configs\namespace.subnamespace.Config.php
        $path = PATH_CONFIGURATION . '/' .
          str_replace('\\', '.', $className) . '.php';
      }
      else
      {
        // trim path
        $className = substr($className, 1 + strlen($namespace));

        // full path to class file
        $path = $options['root'] . '/' . str_replace('\\', '/', $className) . '.php';
      }

      // include file
      if (!file_exists($path) || !include($path))
        return false;

      return true;
    }

    return false;
  }
}

Kernel::selfInit();