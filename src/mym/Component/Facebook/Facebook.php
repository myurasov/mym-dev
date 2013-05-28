<?php

/**
 * Facebook API interaction
 *
 * @todo Support for paging in graph api
 * @copyright 2012, Mikhail Yurasov
 * @version 1.0
 */

namespace mym\Component\Facebook;

class Facebook
{
  private $appId;
  private $secret;
  private $state;
  private $accessToken;
  private $accessTokenExpiration; // [seconds]
  private $certFile;

  private $fetchApiContents = true; // fetch api contents for image apis?
  private $apiContentType; // last content type returned by api call
  private $apiUrl; // last url used for api call

  private $returnArray = false; // data format (obj/array)

  private $debug = false;

  /**
   * Constructor
   * @param array $params [appId, secret, certFile]
   */
  public function __construct($params)
  {
    $this->appId = $params['appId'];
    $this->secret = $params['secret'];
    if (isset($params['certFile'])) $this->certFile = $params['certFile'];
  }

  /**
   * Get Curl handle
   *
   * @param string $url
   * @return curl_handle
   */
  private function _getCurl($url)
  {
    $ch = curl_init($url);

    $curlOptions = array(
      CURLOPT_CONNECTTIMEOUT  => 10,
      CURLOPT_RETURNTRANSFER  => true,
      CURLOPT_TIMEOUT         => 60,
      CURLOPT_SSLVERSION      => 3,
      CURLOPT_FOLLOWLOCATION  => true,
      CURLOPT_VERBOSE         => $this->debug
    );

    curl_setopt_array($ch, $curlOptions);

    // certificate
    if (!is_null($this->certFile))
      curl_setopt($ch, CURLOPT_CAINFO, $this->certFile);

    return $ch;
  }

  /**
   * Get login url
   *
   * Full list of parameters:
   * http://developers.facebook.com/docs/reference/dialogs/oauth/
   *
   * @param array $params
   */
  public function getLoginUrl($params = array())
  {
    $this->state = md5(uniqid(mt_rand(), true));

    $url = "https://www.facebook.com/dialog/oauth?";

    $params = array_merge(array(
      'redirect_uri' => null,
      'scope' => null,
      'response_type' => 'code',
      'display' => 'page' // page|popup
      ), $params, array(
        'client_id' => $this->appId,
        'state' => $this->state,
        'response_type' => 'code'
    ));

    $url .= http_build_query($params);

    return $url;
  }

  public function retrieveAccessToken($code, $redirectUrl)
  {
    if (!is_null($this->accessToken))
      return $this->accessToken;

    // build acces token url

    $params = array(
      'client_id' => $this->appId,
      'client_secret' => $this->secret,
      'redirect_uri' => $redirectUrl,
      'code' => $code
    );

    $url = 'https://graph.facebook.com/oauth/access_token?';
    $url .= http_build_query($params);

    // get access token

    $ch = $this->_getCurl($url);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $this->apiUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $error = curl_error($ch);
    curl_close($ch);

    if ($httpCode != 200) {
      throw new \Exception('Failed to get access token: ' .  $error);
    }

    $parts = array();
    parse_str($data, $parts);
    $this->accessToken = $parts['access_token'];
    $this->accessTokenExpiration = $parts['expires'];

    return $this->accessToken;
  }

  /**
   * Make call to GraphAPI
   *
   * @param string $path
   */
  public function graphApi($path, $method = "GET", $data = [])
  {
    // get graph api url
    $url = 'https://graph.facebook.com/' . $path;

    $ch = $this->_getCurl($url);

    $data["access_token"] = $this->accessToken;
    $query = http_build_query($data, null, "&");

    if ($method == "GET") {
      $url .= '?' . $query;
      curl_setopt($ch, CURLOPT_URL, $url);
    } else if ($method == "POST") {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    }

    if (!$this->fetchApiContents) {
      curl_setopt ($ch, CURLOPT_NOBODY, true);
    }

    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $this->apiContentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $this->apiUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);

    if (preg_match("/json|javascript/i", $this->apiContentType)) {
      $data = json_decode($data, $this->returnArray);
    }

    if ($httpCode == 400)
    {
      throw new OAuthException(
        is_array($data) ? $data["error"]["message"] : $data->error->message,
        is_array($data) ? $data["error"]["code"] : $data->error->code
      );
    }
    else if ($httpCode > 400)
    {
      throw new \Exception("Call to Graph API failed");
    }

    return $this->fetchApiContents ? $data : $this->apiUrl;
  }

  public function getState()
  {
    return $this->state;
  }

  public function getAccessTokenExpiration()
  {
    return $this->accessTokenExpiration;
  }

  public function setAccessToken($accessToken)
  {
    $this->accessToken = $accessToken;
  }

  public function getAccessToken($accessToken)
  {
    $this->accessToken = $accessToken;
  }

  public function setCertFile($certFile)
  {
    $this->certFile = $certFile;
  }

  public function getCertFile()
  {
    return $this->certFile;
  }

  public function getApiContentType()
  {
    return $this->apiContentType;
  }

  public function getApiUrl()
  {
    return $this->apiUrl;
  }

  public function getFetchApiContents()
  {
    return $this->fetchApiContents;
  }

  public function setFetchApiContents($fetchApiContents)
  {
    $this->fetchApiContents = $fetchApiContents;
  }

  public function getReturnArray() {
    return $this->returnArray;
  }

  public function setReturnArray($returnArray) {
    $this->returnArray = $returnArray;
  }

  public function getDebug() {
    return $this->debug;
  }

  public function setDebug($debug) {
    $this->debug = $debug;
  }
}
