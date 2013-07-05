<?php

namespace mym\Routing;

use Symfony\Component\HttpFoundation\Request;

class AbstractUrlMatcher implements UrlMatcherInterface
{
  protected $controller;

  public function match(Request & $request)
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