<?php

namespace mym\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use mym\Exception\NotFoundException;

class DefaultRouter implements RouterInterface
{
  private $controller;
  private $action;

  public function route(Request &$request)
  {
    $matches = array();
    $controller = '';
    $action = '';

    $path = $request->getPathInfo();

    // remove trailing slash
    if (substr($path, -1) == '/')
      $path = substr($path, 0, strlen($path) - 1);

    if ($path == '')
    {
      $controller = 'Index';
      $action = 'index';
    }
    // fallback
    else if (preg_match('#^/([a-z0-9/]+?)(?:/([a-z0-9]+))?$#i', $path, $matches))
    {
      $controller = $matches[1];
      $action = count($matches) > 2 ? $matches[2] : 'index';
    }
    else
    {
      throw new NotFoundException();
    }

    $controller = \mym\PROJECT_NAME . '\Controller\\' . $controller . 'Controller';
    $action = $action . 'Action';

    if (class_exists($controller) && in_array($action, get_class_methods($controller)))
    {
      $this->action = $action;
      $this->controller = $controller;
    }
    else
    {
      throw new NotFoundException("Controller \"$controller::$action\" not found");
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