<?php

/**
 * @copyright 2012, Mikhail Yurasov
 * @package mym
 */

namespace mym\Exception;

use mym\Exception\HTTPException;

class HttpMethodNotAllowedException extends HTTPException {
  public function __construct($message = 'HTTP method is not allowed', $code = 405, $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}