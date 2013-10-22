<?php

/**
 * Facebook authentication controller trait
 *
 * @copyright 2012-2013, Mikhail Yurasov
 */

namespace mym\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use mym\Component\Facebook\Facebook;

trait FacebookAuthControllerTrait {

  protected $facebookCallbackUrl; // callback url, without parameters

  protected $facebookConfig = array(
    'appId' => null,
    'secret' => null,
    'certFile' => null
  );

  protected $facebookScope = ''; // access scope
  protected $facebookAccessToken = '';

  /**
   * @var Response
   */
  protected $response;

  /**
   * @var Request
   */
  protected $request;

  /**
   * @var Session
   */
  protected $session;

  public function facebookLoginAction(Request $request) {
    $response = new Response();
    $response->setPrivate();

    // url to return to
    $returnUrl = $request->query->get("returnUrl", $request->server->get("HTTP_REFERER"));
    $returnUrl = empty($returnUrl) ? $request->server->get("HTTP_REFERER") : $returnUrl;

    // get login url

    $fb = new Facebook($this->facebookConfig);

    $url = $fb->getLoginUrl(array(
      'display' => 'popup',
      'scope' => $this->facebookScope,
      'redirect_uri' => $this->getRedirectUrl($returnUrl)
    ));

    // save state (CSRF token)
    $session = new Session();
    $session->set("facebookState", $fb->getState());

    // redirect to login page
    $response->headers->set("Location", $url);

    return $response;
  }

  // ?returnUrl=<string>
  public function facebookCallbackAction(Request $request) {

    $this->request = $request;

    // url to return to
    $returnUrl = $request->query->get('returnUrl');

    // create response
    $this->response = new RedirectResponse($returnUrl);

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

      $this->setFacebookAccessToken($facebookAccessToken);

      //

      $this->onAuthenticateWithFacebook();
    }
    else {
      // user pressed cancel
      $this->onAuthenticateWithFacebookError();
    }

    // redirect back
    return $this->response;
  }

  private function getRedirectUrl($returnUrl = "") {
    return $this->facebookCallbackUrl . '?returnUrl=' . urlencode($returnUrl);
  }

  /**
   * Called when user succesfully authenticated by Facebook
   */
  public function onAuthenticateWithFacebook(){}

  /**
   * Called when user presses cancel
   */
  public function onAuthenticateWithFacebookError(){}

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

  /**
   * @return Session
   */
  public function getSession() {
    return $this->session;
  }

  public function setSession($session) {
    $this->session = $session;
  }

  public function getResponse() {
    return $this->response;
  }

  public function setResponse($response) {
    $this->response = $response;
  }

  public function getRequest() {
    return $this->request;
  }

  public function setRequest($request) {
    $this->request = $request;
  }

  // </editor-fold>
}