<?php

/**
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym;

trait Singleton {
  private static $instance;

  public static function getInstance() {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }
}