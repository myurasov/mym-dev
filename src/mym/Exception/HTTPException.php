<?php

/**
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Exception;

use mym\Exception\Exception;

class HTTPException extends Exception
{
  public static function createFromCode($code, $message = null)
  {
    if ($code === HttpBadRequestException::CODE) {
      return is_null($message) ? new HttpBadRequestException() : new HttpBadRequestException($message);
    }

    if ($code === HttpForbiddenException::CODE) {
      return is_null($message) ? new HttpForbiddenException() : new HttpForbiddenException($message);
    }

    if ($code === HttpMethodNotAllowedException::CODE) {
      return is_null($message) ? new HttpMethodNotAllowedException() : new HttpMethodNotAllowedException($message);
    }

    if ($code === HttpNotFoundException::CODE) {
      return is_null($message) ? new HttpNotFoundException() : new HttpNotFoundException($message);
    }

    if ($code === HttpNotImplementedException::CODE) {
      return is_null($message) ? new HttpNotImplementedException() : new HttpNotImplementedException($message);
    }

    return is_null($message) ? new self($code) : new self($code, $message);
  }
}