<?php

namespace ymF\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use ymF\Exception\NotFoundException;

class DefaultRouter implements RouterInterface
{
  private $controller;
  private $action;

  public function route(Request $request)
  {
    $matches = array();
    $controller = "";
    $action = "";

    $path = $request->getPathInfo();

    if ($path == "/")
    {
      $controller = "Index";
      $action = "index";
    }
    else if (preg_match("#/([a-z/]+?)(?:/([a-z]+))?/?$#i", $path, $matches)) // fallback
    {
      $controller = $matches[1];
      $action = count($matches) > 2 ? $matches[2] : "default";
    }
    else
    {
      throw new NotFoundException();
    }

    $controller = \ymF\PROJECT_NAME . "\Controller\\" . $controller . "Controller";
    $action = $action . "Action";

    if (class_exists($controller) && in_array($action, get_class_methods($controller)))
    {
      $this->action = $action;
      $this->controller = $controller;
    }
    else
    {
      throw new NotFoundException();
    }

    return $this;
  }

  public function getController()
  {
    return $this->controller;
  }

  public function getAction()
  {
    return $this->action;
  }
}