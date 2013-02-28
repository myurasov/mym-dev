<?php

/**
 * Facebook authentication controller trait
 *
 * @copyright 2012-2013, Mikhail Yurasov
 */

namespace mym\Component\Facebook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use mym\Component\Facebook\Facebook;

trait FacebookAuthController {

  private $facebookCallbackUrl; // callback url, without parameters

  private $facebookConfig = array(
    'appId' => null,
    'secret' => null,
    'certFile' => null
  );

  private $facebookScope = ''; // access scope

  private $facebookAccessToken = '';

  /**
   * @var Session
   */
  private $session;

  public function facebookLoginAction(Request $request) {
    $response = new Response();
    $response->setPrivate();

    // url to return to
    $returnUrl = $request->query->get("returnUrl", $request->server->get("HTTP_REFERER"));

    // get login url

    $fb = new Facebook($this->facebookConfig);

    $url = $fb->getLoginUrl(array(
      'display' => 'popup',
      'scope' => $this->facebookScope,
      'redirect_uri' => $this->getRedirectUrl($returnUrl)
    ));

    // save state (CSRF token)
    $sesion = new Session();
    $sesion->set("facebookState", $fb->getState());

    // redirect to login page
    $response->headers->set("Location", $url);

    return $response;
  }

  // ?returnUrl=<string>
  public function facebookCallbackAction(Request $request) {

    // url to return to
    $returnUrl = $request->query->get('returnUrl');

    if (!$request->query->has('error')) {

      // start session
      $session = new Session();

      // check csrf token
      if (!$session->has("facebookState") || $session->get("facebookState") != $request->query->get("state")) {
        throw new \Exception("States don't match");
      }

      $session->remove("facebookState");

      // get acces token

      $fb = new Facebook($this->facebookConfig);

      $facebookAccessToken = $fb->retrieveAccessToken(
        $request->query->get('code'),
        $this->getRedirectUrl($returnUrl)
      );

      $this->setSession($session);
      $this->setFacebookAccessToken($facebookAccessToken);
      $this->onAuthenticateWithFacebook($facebookAccessToken);
    }
    else {
      // user pressed cancel
    }

    // redirect back
    return new RedirectResponse($returnUrl);
  }

  private function getRedirectUrl($returnUrl = "") {
    return $this->facebookCallbackUrl . '?returnUrl=' . urlencode($returnUrl);
  }

  /**
   * Called when user succesfully authenticated by Facebook
   */
  abstract public function onAuthenticateWithFacebook();

  // <editor-fold defaultstate="collapsed" desc="Accessors">

  public function getFacebookCallbackUrl() {
    return $this->facebookCallbackUrl;
  }

  public function setFacebookCallbackUrl($facebookCallbackUrl) {
    $this->facebookCallbackUrl = $facebookCallbackUrl;
  }

  public function getFacebookConfig() {
    return $this->facebookConfig;
  }

  public function setFacebookConfig($facebookConfig) {
    $this->facebookConfig = $facebookConfig;
  }

  public function getFacebookScope() {
    return $this->facebookScope;
  }

  public function setFacebookScope($facebookScope) {
    $this->facebookScope = $facebookScope;
  }

  public function getFacebookAccessToken() {
    return $this->facebookAccessToken;
  }

  public function setFacebookAccessToken($facebookAccessToken) {
    $this->facebookAccessToken = $facebookAccessToken;
  }

  public function getSession() {
    return $this->session;
  }

  public function setSession($session) {
    $this->session = $session;
  }

  // </editor-fold>
}