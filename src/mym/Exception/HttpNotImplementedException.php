<?php

/**
 * @copyright 2012, Mikhail Yurasov
 * @package mym
 */

namespace mym\Exception;

use mym\Exception\HTTPException;

class HttpNotImplementedException extends HTTPException {
  public function __construct($message = "Not implemented", $code = 501, $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}