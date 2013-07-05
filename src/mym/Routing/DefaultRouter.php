<?php

namespace mym\Routing;

use Symfony\Component\HttpFoundation\Request;
use mym\Exception\HttpNotFoundException;

class DefaultRouter extends AbstractRouter
{
  /**
   * @var UrlMatcherInterface[]
   */
  protected $urlMatchers = [];

  public function __construct()
  {
    $this->urlMatchers[] = new DefaultUrlMatcher();
  }

  public function route(Request & $request)
  {
    // run through the list of url matchers
    if (!$this->runMatchers($request)) {
      throw new HttpNotFoundException();
    }
  }

  private function runMatchers(Request & $request)
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