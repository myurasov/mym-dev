<?php

/**
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Exception;

use mym\Exception\HTTPException;

class HttpBadRequestException extends HTTPException {

  const CODE = 400;

  /**
   * The request could not be understood by the server due to malformed syntax. The client SHOULD NOT repeat the request without modifications.
   */
  public function __construct($message = 'Bad request', $code = 400, $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}