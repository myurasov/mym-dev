<?php

/**
 * Resource not found exception
 *
 * @copyright 2012 Mikhail Yurasov
 * @package ymF
 */

namespace ymF\Exception;

use ymF\Exception\HTTPException;

class NotFoundException extends HTTPException
{
  public function __construct($message = "Not found", $code = 404, $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}