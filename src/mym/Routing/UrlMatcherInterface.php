<?php

namespace mym\Routing;

use Symfony\Component\HttpFoundation\Request;

interface UrlMatcherInterface
{
  public function match(Request & $request);

  /**
   * @return callable
   */
  public function getController();
}