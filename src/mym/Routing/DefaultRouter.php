<?php

namespace mym\Routing;

use Symfony\Component\HttpFoundation\Request;
use mym\Exception\HttpNotFoundException;

class DefaultRouter extends AbstractRouter
{
  public function __construct()
  {
    $this->urlMatchers[] = new DefaultUrlMatcher();
  }
}