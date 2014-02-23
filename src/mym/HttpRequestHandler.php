<?php

namespace mym;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use mym\Exception\HTTPException;
use mym\Exception\HttpMethodNotAllowedException;

class HttpRequestHandler
{
  /**
   * @var Request
   */
  private $request;

  /**
   * @var Response
   */
  private $response;

  private $controller;

  private function handleJsonRequest()
  {
    if (0 === strpos($this->request->headers->get('Content-Type'), 'application/json')) {
      $data = json_decode($this->request->getContent(), true);
      $this->request->request->replace(is_array($data) ? $data : []);
    }
  }

  private function processRequest()
  {
    // route

    $routerClassName = Config::$options['http']['router'];

    if (class_exists($routerClassName)) {
      $router /* @var $router Routing\RouterInterface */ = new $routerClassName();
    } else {
       throw new \Exception('Router class not found: ' . $routerClassName);
    }

    $router->route($this->request);
    $this->controller = $router->getController();

    // call controller
    $this->response = $this->callController();
  }

  private function callController()
  {
    if (is_array($this->controller) && count($this->controller) >= 2 /* [className, methodName] */) {

      $controllerClass = $this->controller[0];
      $action = $this->controller[1];

      if (!class_exists($controllerClass)) {
        throw new Exception\HttpNotFoundException("Class '{$controllerClass}' not found");
      }

      if (!method_exists($controllerClass, $action)) {
        throw new Exception\HttpNotFoundException("Method '{$controllerClass}::{$action}' does not exist");
      }

      $response = call_user_func([new $controllerClass($this->request), $action], $this->request);

      if (false === $response) {
        throw new \Exception('Error calling controller');
      }

      return $response;

    } else if (is_callable($this->controller)) {
      return call_user_func($this->controller, $this->request);
    } else {
      throw new \Exception('Controller is not callable');
    }

    if (!($this->response instanceof Response)) {
      throw new \Exception("Response object should be returned");
    }
  }

  public function handle()
  {
    $this->request = Request::createFromGlobals();

    try {

        $this->handleJsonRequest();
        $this->processRequest();

        if (!($this->response instanceof Response)) {
          throw new \Exception('Controller should return Response object');
        }

      } catch (\Exception $e) {

        if (Config::$options['http']['catchExceptions']) {

          // determine acceptable content types
          $acceptableTypes = $this->request->getAcceptableContentTypes();

          // http code
          $httpCode = ($e instanceof HTTPException) ? $e->getCode() : 500;

          if (is_array($acceptableTypes) && in_array('application/json', $acceptableTypes)) {

            // return JSON error message
            $this->response = new JsonResponse([
                'error' => $e->getCode(),
                'message' => $e->getMessage()
              ], $httpCode);

          } else {
            // return text/html error message
            $this->response = new Response($e->getMessage(), $httpCode);
          }

          // add Allow header (required with HTTP 405 Method Not Allowed)
          if ($e instanceof HttpMethodNotAllowedException) {
            $this->response->headers->set('Allow', $e->getAllowedMethods());
          }

        } else {

          // rethrow exception
          throw $e;

        }
    }

    // force HTTP 1.1
    if (Config::$options['http']['forceProtocolVersion']) {
      $this->response->setProtocolVersion(Config::$options['http']['forceProtocolVersion']);
    }

    // send response
    $this->response->send();
  }
}