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
    define('mym\VERSION', '1.2-dev');
    define('mym\ERROR_OK', 0);
    define('mym\ERROR_MISC', -1);
  }
}

Kernel::init();