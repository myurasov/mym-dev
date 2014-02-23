<?php

namespace mym\Routing;

use Symfony\Component\HttpFoundation\Request;
use mym\Exception\HttpNotFoundException;

abstract class AbstractRouter implements RouterInterface
{
  protected $controller;

  /**
   * @var UrlMatcherInterface[]
   */
  protected $urlMatchers = [];

  /**
   * @return callable
   */
  public function getController()
  {
    return $this->controller;
  }

  public function route(Request & $request)
  {
    // run through the list of url matchers
    if (!$this->runMatchers($request)) {
      throw new HttpNotFoundException();
    }
  }

  protected function runMatchers(Request & $request)
  {
    for ($i = 0; $i < count($this->urlMatchers); $i++) {
      $this->urlMatchers[$i]->match($request);

      if ($this->controller = $this->urlMatchers[$i]->getController()) {
        return true;
      }
    }

    return false;
  }
}