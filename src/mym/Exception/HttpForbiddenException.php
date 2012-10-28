<?php

/**
 * @copyright 2012, Mikhail Yurasov
 * @package mym
 */

namespace mym\Exception;

use mym\Exception\HTTPException;

class HttpForbiddenException extends HTTPException {
  public function __construct($message = "Forbidden", $code = 403, $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}