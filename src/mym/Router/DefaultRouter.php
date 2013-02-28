<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Router;

use mym\Router\RouterInterface;
use Symfony\Component\HttpFoundation\Request;

class DefaultRouter implements RouterInterface {

  protected $controller;
  protected $action;

  protected function match(Request & $request) {

    $m = [];

    if (preg_match('#^/([a-z0-9/]+?)(?:/([a-z0-9]+))?$#i', $request->getPathInfo(), $m)) {
      $this->controller = $m[1];
      $this->action = count($m) > 2 ? $m[2] : "index";
      return true;
    }

    return false;
  }

  /**
   * Resolves controller class and action names to fully-qualified ones
   * @throws HttpNotFoundException
   */
  protected function resolve() {

    if (substr($this->controller, 0, 1) != "\\" /* if path is not absolute */) {
      // resolve to class name
      $this->controller = str_replace("/", "\\", $this->controller);
      $this->controller = \mym\Config::get("http.controllerNamespace") .
        "\\" . $this->controller . 'Controller';
    }

    $this->action = $this->action . 'Action';

    if (!class_exists($this->controller) || !in_array($this->action, get_class_methods($this->controller))) {
      throw new HttpNotFoundException("Action \"{$this->controller}::{$this->action}\" not found");
    }

  }

  public function route(Request & $request) {
    $this->match($request);
    $this->resolve();
  }

  //

  public function getController() {
    return $this->controller;
  }

  public function getAction() {
    return $this->action;
  }
}