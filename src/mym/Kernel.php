<?php

/**
 * mym framework
 * @copyright 2009-2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use mym\Exception\Exception;
use mym\Exception\HTTPException;

class Kernel
{
  // Root namespaces registered for autoloading
  private static $autoload = array();

  /**
   * Handle HTTP request
   */
  public static function handleHttpRequest()
  {
    $request;
    $response;

    $processRequest = function() use (&$request, &$response) {
      // create request
      $request = Request::createFromGlobals();

      // Route

      $httpRouter = Config::$options["http"]["router"];
      $httpRouter = new $httpRouter;

      $httpRouter->route($request);
      $controller = $httpRouter->getController();
      $action = $httpRouter->getAction();

      // Call controller

      $controller = new $controller;
      $response = $controller->$action($request);

      if (!($response instanceof Response))
        throw new Exception("Response object should be returned");
    };

    if (Config::$options["http"]["catchExceptions"])
    {
      try
      {
        $processRequest();
      }
      catch (\Exception $e)
      {
        // determine acceptable content types
        $acceptableTypes = $request->getAcceptableContentTypes();

        // http code
        $httpCode = ($e instanceof HTTPException) ? $e->getCode() : 500;

        if (is_array($acceptableTypes) && in_array('application/json', $acceptableTypes))
        {
          // return JSON
          $response = new JsonResponse(array(
            'error' => $e->getCode(),
            'message' => $e->getMessage()
          ), $httpCode);
        }
        else
        {
          // return text/html
          $response = new Response($e->getMessage(), $httpCode);
        }
      }
    }
    else
    {
      $processRequest();
    }

    // send response
    $response->send();
  }

  /**
   * Initialize mym
   *
   */
  public static function init()
  {
    // Define version

    // major.minor<.change>< status>
    define('mym\VERSION', '1.1-dev');

    if (!defined('mym\HOSTNAME')) {
      define('mym\HOSTNAME', 'localhost');
    }

    if (!defined('mym\DEVELOPMENT')) {
      define('mym\DEVELOPMENT', false);
    }

    // Define paths:

    // Project root directory

    if (!defined('mym\PATH_ROOT')) {
      define('mym\PATH_ROOT', realpath(__DIR__ . '/../../..'));
    }

    // Core executable files
    if (!defined('mym\PATH_SRC')) {
      define('mym\PATH_SRC', PATH_ROOT . '/src');
    }

    // Web documents
    if (!defined('mym\PATH_WWW')) {
      define('mym\PATH_WWW', PATH_SRC . '/www');
    }

    // Command-line interface
    if (!defined('mym\PATH_CLI')) {
      define('mym\PATH_CLI', PATH_SRC . '/cli');
    }

    // Variable application data
    if (!defined('mym\PATH_DATA')) {
      define('mym\PATH_DATA', PATH_ROOT . '/data');
    }

    // Temporary data
    if (!defined('mym\PATH_TEMP')) {
      define('mym\PATH_TEMP', PATH_DATA . '/temp');
    }

    // Code modules and root namespace
    if (!defined('mym\PATH_MODULES')) {
      define('mym\PATH_MODULES', PATH_SRC . '/modules');
    }

    // Templates
    if (!defined('mym\PATH_TEMPLATES')) {
      define('mym\PATH_TEMPLATES', PATH_SRC . '/templates');
    }

    // Resource files
    if (!defined('mym\PATH_RESOURCES')) {
      define('mym\PATH_RESOURCES', PATH_SRC . '/resources');
    }

    // Bundled libraries
    if (!defined('mym\PATH_LIBRARIES')) {
      define('mym\PATH_LIBRARIES', PATH_SRC . '/libraries');
    }

    // Config classes
    if (!defined('mym\PATH_CONFIGURATION')) {
      define('mym\PATH_CONFIGURATION', PATH_SRC . '/configs');
    }

    // Logs
    if (!defined('mym\PATH_LOGS')) {
      define('mym\PATH_LOGS', PATH_DATA . '/logs');
    }

    // Define errors:

    define('mym\ERROR_OK', 0);
    define('mym\ERROR_MISC', -1);

    // Register autoloader function
    spl_autoload_register(__CLASS__ . '::autoload');
  }

  /**
   * Register namespace for autoloading
   *
   * @param <type> $namespace
   * @param <type> $root
   * @param <type> $relocateConfig Search configs in mym\PATH_CONFIGURATION as namespace.subnamespace.Config.php
   */
  public static function registerAutoloadNamespace(
    $namespace, $root = null, $relocateConfig = false)
  {
    self::$autoload[$namespace] = array(
      'root'          => $root,
      'config_reloc'  => $relocateConfig
    );
  }

  /**
   * Loads required class on first usage
   *
   * Something\Another\Config - Something.Another.Config.php
   * Something\Another\Class - Something\Another\Class.php
   *
   * @param string $className
   */
  public static function autoload($className)
  {
    // remove leading \
    if (substr($className, 0, 1) == '\\') {
      $className = substr($className, 1);
    }

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
      if (file_exists($path)) {
        if (include($path)) {
          return true;
        }
      }

      return false;
    }

    return false;
  }
}

Kernel::init();