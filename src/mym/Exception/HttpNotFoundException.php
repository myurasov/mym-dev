<?php

/**
 * @copyright 2012, Mikhail Yurasov
 * @package mym
 */

namespace mym\Exception;

use mym\Exception\HTTPException;

class HttpNotFoundException extends HTTPException {
  public function __construct($message = "Not found", $code = 404, $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}