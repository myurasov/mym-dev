<?php

/**
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Exception;

use mym\Exception\HTTPException;

class HttpNotFoundException extends HTTPException {

  const CODE = 404;

  /**
   * The server has not found anything matching the Request-URI. No indication is given of whether the condition is temporary or permanent. The 410 (Gone) status code SHOULD be used if the server knows, through some internally configurable mechanism, that an old resource is permanently unavailable and has no forwarding address. This status code is commonly used when the server does not wish to reveal exactly why the request has been refused, or when no other response is applicable.
   */
  public function __construct($message = "Not found", $code = 404, $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}