<?php

namespace mym\ODM\Traits;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use mym\Exception\HttpForbiddenException;

trait AuthenticateTrait
{
  /**
   * Check if user is authenticated.
   *
   * @param Request $request
   * @param bool $required
   * @throws HttpForbiddenException
   * @return Current user
   */
  public static function authenticate(Request $request, $required = false)
  {
    $user = null;

    // start session
    $session = new Session();
    $session->start();
    $request->setSession($session);


    if ($session->has("userId")) {
      $user = self::load($session->get("userId"));
    }

    if ($user === null && $required) {
      throw new HttpForbiddenException("User is not authenticated");
    }

    return $user;
  }
}