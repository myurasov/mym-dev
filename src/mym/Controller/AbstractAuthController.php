<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Controller;

class AbstractAuthController
{
  protected $loggedInParameter = null;

  protected function addLoggedInParameter()
  {
    $url = $this->response->getTargetUrl();

    if (!empty($this->loggedInParameter) && false === strstr($url, $this->loggedInParameter)) {

      if (false === strstr($url, "?")) {
         $url .= "?";
      } else {
        $url .= "&";
      }

      $url .= $this->loggedInParameter;
      $this->response->setTargetUrl($url);
    }
  }

  protected function removeLoggedInParameter()
  {
    $url = $this->response->getTargetUrl();

    if (!empty($this->loggedInParameter) && false !== strstr($url, $this->loggedInParameter)) {
      $url = preg_replace("#(\?|\&)" . preg_quote($this->loggedInParameter) . "#", "", $url);
      $this->response->setTargetUrl($url);
    }
  }
}