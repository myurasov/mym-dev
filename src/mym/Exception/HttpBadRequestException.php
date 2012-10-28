<?php

/**
 * @copyright 2012, Mikhail Yurasov
 * @package mym
 */

namespace mym\Exception;

use mym\Exception\HTTPException;

class HttpBadRequestException extends HTTPException {
  public function __construct($message = 'Bad request', $code = 400, $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}