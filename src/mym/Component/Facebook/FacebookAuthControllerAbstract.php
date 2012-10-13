<?php

/**
 * Facebook authentication controller
 *
 * @copyright 2012, Mikhail Yurasov
 */

namespace mym\Component\Facebook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use mym\Component\Facebook\Facebook;

abstract class FacebookAuthControllerAbstract {

  private $callbackUrl; // callback url, without parameters

  private $facebookConfig = array(
    'appId' => null,
    'secret' => null,
    'certFile' => null
  );

  private $scope = ''; // access scope

  private $returnUrl; // return url

  public function loginAction(Request $request) {
    $response = new Response();

    // start session
    if (session_id() == '') {
      session_start();
    }

    $response->setPrivate();

    // url to return to
    $this->returnUrl = $request->query->get(
      'returnUrl',
      $this->returnUrl ?: $request->server->get('HTTP_REFERER')
    );

    // get login url

    $fb = new Facebook($this->facebookConfig);

    $url = $fb->getLoginUrl(array(
      'display' => 'popup',
      'scope' => $this->scope,
      'redirect_uri' => $this->getCallbackUrl()
    ));

    // save state (CSRF token)
    $_SESSION['facebookState'] = $fb->getState();

    // redirect to login page
    $response->headers->set('Location', $url);

    return $response;
  }

  public function logoutAction(Request $request) {
    $response = new Response();
    $response->setPrivate();

    // delete session
    session_start();
    session_destroy();
    $response->headers->setCookie(new Cookie(session_name(), null));

    // url to return to
    $this->returnUrl = $request->query->get(
      'returnUrl',
      $this->returnUrl ?: $request->server->get('HTTP_REFERER')
    );

    $response->headers->set('Location', $this->returnUrl);

    return $response;
  }

  // ?returnUrl=<string>
  public function callbackAction(Request $request) {
    $response = new Response();

    // url to return to
    $this->returnUrl = $request->query->get('returnUrl');

    if (!$request->query->has('error')) {
      // start session
      if (session_id() == '') {
        session_start();
      }

      $response->setPrivate();

      // check csrf token
      if (!isset($_SESSION['facebookState']) ||
        $_SESSION['facebookState'] != $request->query->get('state'))
        throw new \Exception("States don't match");

      unset($_SESSION['facebookState']);

      // end session
      session_destroy();

      // get acces token

      $fb = new Facebook($this->facebookConfig);

      $facebookAccessToken = $fb->retrieveAccessToken(
        $request->query->get('code'),
        $this->getCallbackUrl()
      );

      $this->onAuthenticate($facebookAccessToken);
    }
    else {
      // user pressed cancel
    }

    // redirect back
    $response->headers->set("Location", $this->returnUrl);

    return $response;
  }

  public function getCallbackUrl() {
    return $this->callbackUrl . '?returnUrl=' . urlencode($this->returnUrl);
  }

  /**
   * @param string $callbackUrl Callback url (e.g.: http://baseurl/FacebookAuth/callback)
   */
  public function setCallbackUrl($callbackUrl) {
    $this->callbackUrl = $callbackUrl;
  }

  /**
   * Called when user succesfully authenticated by Facebook
   * @param string $accessToken
   */
  public abstract function onAuthenticate($accessToken);

  //

  public function getFacebookConfig() {
    return $this->facebookConfig;
  }

  public function setFacebookConfig($facebookConfig) {
    $this->facebookConfig = $facebookConfig;
  }

  public function getScope() {
    return $this->scope;
  }

  public function setScope($scope) {
    $this->scope = $scope;
  }

  public function getReturnUrl() {
    return $this->returnUrl;
  }

  public function setReturnUrl($returnUrl) {
    $this->returnUrl = $returnUrl;
  }

}