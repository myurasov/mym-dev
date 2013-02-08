<?php

namespace mym\Component\PayPal;

class IPNMessage {

  const ENDPOINT_SANDBOX = "https://www.sandbox.paypal.com/";
  const ENDPOINT_PRODUCTION = "https://www.paypal.com/";

  private $postData;
  private $message = [];
  private $isVerified = false;

  /**
   * @var Configuration
   */
  private $configuration;

  public function __construct() {
    $this->postData = file_get_contents("php://input");
  }

  public function setPostData($postData) {
    $this->postData = $postData;
  }

  public function parseMessage() {
    parse_str($this->postData, $this->message);
  }

  /**
   * Verify that message is authentic
   * Informs PayPal that message is received and prevents repeated messages
   */
  public function verify() {
    $url = ($this->configuration->getIsSandbox() ? self::ENDPOINT_SANDBOX : self::ENDPOINT_PRODUCTION)
      . "/cgi-bin/webscr?cmd=_notify-validate&"
      . $this->postData;

    $payPalResponse = file_get_contents($url);
    $this->isVerified = ($payPalResponse == "VERIFIED");

    return $this->isVerified;
  }


  // <editor-fold defaultstate="collapsed" desc="Accessors">

  public function getMessage() {
    return $this->message;
  }

  public function getIsVerified() {
    return $this->isVerified;
  }

  public function getConfiguration() {
    return $this->configuration;
  }

  public function setConfiguration($configuration) {
    $this->configuration = $configuration;
  }

  public function getPostData() {
    return $this->postData;
  }

  // </editor-fold>
}