<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Component\Auth;

use mym\Singleton;
use mym\Util\Strings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

abstract class AbstractAuthService
{
  use Singleton;

  protected $tokenName;
  protected $tokenLifetime;
  protected $cookieDomain;

  abstract public function getUserId($token);

  abstract public function setUserId($token, $userId);

  public function createToken($userId)
  {
    $token = Strings::createRandomString(null, Strings::ALPHABET_ALPHANUMERICAL, 256);
    $this->setUserId($token, $userId, true);
    return $token;
  }

  public function getTokenFromRequest(Request $request)
  {
    // get from cookie
    $token = $request->cookies->get($this->tokenName);

    // try GET
    if (!$token) {
      $token = $request->get($this->tokenName);
    }

    return $token;
  }

  public function getUserIdFromRequest(Request $request)
  {
    $token = $this->getTokenFromRequest($request);

    if ($token) {
      return $this->getUserId($token);
    }

    return false;
  }

  public function saveTokenToCookie(Response $response, $token)
  {
    $cookie = new Cookie(
      $this->tokenName,
      $token,
      time() + $this->tokenLifetime,
      '/',
      $this->cookieDomain,
      false,
      false
    );

    $response->headers->setCookie($cookie);
  }

  public function clearTokenCookie(Response $response)
  {
    $cookie = new Cookie($this->tokenName, '', 0, '/', $this->cookieDomain, false, false);
    $response->headers->setCookie($cookie);
  }

  // <editor-fold defaultstate="collapsed" desc="accessors">

  public function getTokenLifetime()
  {
    return $this->tokenLifetime;
  }

  public function setTokenLifetime($tokenLifetime)
  {
    $this->tokenLifetime = $tokenLifetime;
  }

  public function getTokenName()
  {
    return $this->tokenName;
  }

  public function setTokenName($tokenName)
  {
    $this->tokenName = $tokenName;
  }

  public function getCookieDomain()
  {
    return $this->cookieDomain;
  }

  public function setCookieDomain($cookieDomain)
  {
    $this->cookieDomain = $cookieDomain;
  }

  // </editor-fold>
}