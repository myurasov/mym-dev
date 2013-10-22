<?php

/**
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Exception;

use mym\Exception\HTTPException;

class HttpMethodNotAllowedException extends HTTPException {

  private $allowedMethods;

  /**
   * The method specified in the Request-Line is not allowed for the resource identified by the Request-URI. The response MUST include an Allow header containing a list of valid methods for the requested resource.
   *
   * @param string $allow Allowed methods (eg: "POST, PUT, DELETE")
   */
  public function __construct($message = 'HTTP method is not allowed', $code = 405, $allowedMethods = 'POST', $previous = null) {
    $this->allowedMethods = $allowedMethods;
    parent::__construct($message, $code, $previous);
  }

  public function getAllowedMethods()
  {
    return $this->allowedMethods;
  }

  public function setAllowedMethods($allowedMethods)
  {
    $this->allowedMethods = $allowedMethods;
    return $this;
  }
}