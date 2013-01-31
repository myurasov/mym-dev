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
   * @param string $endpoint
   * @param array $payload
   * @return array Decoded response envelope as assoc array
   */
  public function callAPI($endpoint, $payload) {

    // debug
    if ($this->debug) {
      echo "# endpoint:\n", $endpoint, "\n";
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_VERBOSE, $this->debug);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $payload = array_merge_recursive(array(
      "requestEnvelope" => array(
        "detailLevel" => "ReturnAll",
        "errorLanguage" => "en_US"
      )
    ), $payload);

    // remove nulls from payload
    $payload = array_filter($payload, function ($e) {
      return !is_null($e);
    });

    $payload = json_encode($payload, $this->debug ? JSON_PRETTY_PRINT : 0);

    // debug
    if ($this->debug) {
      echo "\n# payload:\n", $payload, "\n\n";
    }

    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Set the HTTP Headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
      "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
      "X-PAYPAL-SECURITY-USERID: " . $this->configuration->getUserId(),
      "X-PAYPAL-SECURITY-PASSWORD: " . $this->configuration->getPassword(),
      "X-PAYPAL-SECURITY-SIGNATURE: " . $this->configuration->getSignature(),
//      "X-PAYPAL-SERVICE-VERSION: 1.3.0",
      "X-PAYPAL-APPLICATION-ID: " . $this->configuration->getAppId()
    ));

    // make call

    $res = curl_exec($ch);

    // debug
    if ($this->debug) {
      echo "\n# response:\n", $res, "\n\n";
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
