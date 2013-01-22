<?php

/**
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym\Component\PayPal;

use mym\Component\PayPal\Configuration;

class AbstractService {

  /**
   * @var Configuration
   */
  protected $configuration;

  protected $debug = false;

  /**
   * Call to Paypal API
   *
   * @param string $action action name
   * @param string/array $payload payload in JSON format / array
   * @return array Decoded response envelope as assoc array
   */
  protected function callAPI($action = "", $payload) {

    // endpoint url
    $endpointUrl = $this->configuration->getIsSandbox()
      ? Configuration::SANDBOX_ENDPOINT : Configuration::PRODUCTION_ENDPOINT;
    $endpointUrl .= "AdaptivePayments/" . $action;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpointUrl);
    curl_setopt($ch, CURLOPT_VERBOSE, $this->debug);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    if (is_array($payload)) {
      $payload = json_encode($payload, $this->debug ? JSON_PRETTY_PRINT : 0);
    }

    if ($this->debug) {
      echo "\npayload:\n", $payload, "\n";
    }

    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Set the HTTP Headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
      "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
      "X-PAYPAL-SECURITY-USERID: " . $this->configuration->getUserId(),
      "X-PAYPAL-SECURITY-PASSWORD: " . $this->configuration->getPassword(),
      "X-PAYPAL-SECURITY-SIGNATURE: " . $this->configuration->getSignature(),
      "X-PAYPAL-SERVICE-VERSION: 1.3.0",
      "X-PAYPAL-APPLICATION-ID: " . ($this->configuration->getIsSandbox()
        ? Configuration::SANDBOX_APP_ID : $this->configuration->getAppId())
    ));

    // make call

    $res = curl_exec($ch);

    if ($this->debug) {
      echo "\nresponse:\n", $res, "\n";
    }

    $res = json_decode($res, true);

    return $res;
  }

  // <editor-fold defaultstate="collapsed" desc="Accessors">

  public function getDebug() {
    return $this->debug;
  }

  public function setDebug($debug) {
    $this->debug = $debug;
  }

  public function getConfiguration() {
    return $this->configuration;
  }

  public function setConfiguration($configuration) {
    $this->configuration = $configuration;
  }

  // </editor-fold>
}
