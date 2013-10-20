<?php

/**
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym;

trait Singleton {
  protected static $instance;

  public static function getInstance() {
   if (is_null(static::$instance)) {
      static::$instance = new static();
   }

    return static::$instance;
  }

  public function __construct() {
    if (static::$instance) {
      throw new \Exception('Class ' . get_called_class() .' is a singleton');
    } else {
      static::$instance = $this;
    }
  }
}