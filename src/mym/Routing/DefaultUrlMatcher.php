<?php

namespace mym\Routing;

use mym\Config as mymConfig;
use Symfony\Component\HttpFoundation\Request;

class DefaultUrlMatcher extends AbstractUrlMatcher
{
  public function match(Request & $request)
  {
    $class = '';
    $method = '';

    $m = [];

    if (preg_match('#^/([a-z0-9/]+?)(?:/([a-z0-9]+))?/?$#i', $request->getPathInfo(), $m)) {
      $class = mymConfig::$options['http']['controllerNamespace'] . '\\' . $m[1] . 'Controller';
      $class = str_replace('/', '\\', $class);
      $method = (count($m) > 2 ? $m[2] : 'index') . 'Action';
      $this->controller = [$class, $method];
    }
  }
}