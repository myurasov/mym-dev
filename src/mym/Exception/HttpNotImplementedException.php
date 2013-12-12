<?php

/**
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Exception;

use mym\Exception\HTTPException;

class HttpNotImplementedException extends HTTPException {

  const CODE = 501;

  /**
   * The server does not support the functionality required to fulfill the request. This is the appropriate response when the server does not recognize the request method and is not capable of supporting it for any resource.
   */
  public function __construct($message = "Not implemented", $code = 501, $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}