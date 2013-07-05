<?php

namespace mym\Routing;

use Symfony\Component\HttpFoundation\Request;
use mym\Exception\HttpNotFoundException;

class AbstractRouter implements RouterInterface
{
  protected $controller;

  public function route(Request & $request)
  {
  }

  /**
   * @return callable
   */
  public function getController()
  {
    return $this->controller;
  }
}