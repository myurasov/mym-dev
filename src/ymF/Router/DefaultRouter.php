<?php

namespace ymF\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultRouter implements RouterInterface
{
  protected $action = null;
  protected $controller = null;

  /**
   * Get controller funciton
   *
   * @return string
   */
  public function getAction()
  {
    return $this->action;
  }

  /**
   * Get controller class name
   *
   * @return string
   */
  public function getController()
  {
    return $this->controller;
  }

  /**
   * Route request
   *
   * @param Request $request
   * @return RouterInterface
   */
  public function route(Request $request)
  {
    $path = $request->getPathInfo();

    $controller = null;
    $action = null;

    if ($path == "/")
    {
      $controller = "Index";
      $action = "default";
    }
    else
    {
      throw new \Exception("Route not found");
    }

    $controller = \ymF\PROJECT_NAME . "\Controller\\" . $controller . "Controller";
    $action = $action . "Action";
    
    $this->action = $action;
    $this->controller = $controller;

    return $this;
  }
}