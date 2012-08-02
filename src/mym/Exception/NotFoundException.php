<?php

/**
 * Resource not found exception
 *
 * @copyright 2012 Mikhail Yurasov
 * @package mym
 */

namespace mym\Exception;

use mym\Exception\HTTPException;

class NotFoundException extends HTTPException
{
  public function __construct($message = "Not found", $code = 404, $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}