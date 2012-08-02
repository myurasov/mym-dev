<?php

/**
 * OAuth protocol exception
 *
 * @copyright 2012, Mikhail Yurasov
 */

namespace mym\Component\Facebook;

class OAuthException extends \Exception
{
  public function __construct($message = 'OAuth exception', $code = 0, $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}