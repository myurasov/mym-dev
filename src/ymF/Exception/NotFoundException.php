<?php

/**
 * Resource not found exception
 *
 * @copyright 2012 Mikhail Yurasov
 * @package ymF
 */

namespace ymF\Exception;

use ymF\Exception\Exception;

class NotFoundException extends Exception
{
  public function __construct($message = "Not found", $code = null, $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}