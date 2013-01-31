<?php

/**
 * Adaptive Payments service
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym\Component\PayPal\AdaptivePayments;

use mym\Component\PayPal\AbstractService;
use mym\Component\PayPal\Configuration;
use mym\Component\PayPal\AdaptivePayments\PayOptions;

class Service extends AbstractService {

  const ENDPOINT_SANDBOX = "https://svcs.sandbox.paypal.com/AdaptivePayments/";
  const ENDPOINT_PRODUCTION = "https://svcs.paypal.com/AdaptivePayments/";

  /**
   * Create payment
   * @param \mym\Component\Paypal\AdaptivePayments\PayOptions $options
   * @return array Decoded response envelope
   *
    Normal response example:
   *
    array (
      'responseEnvelope' =>
      array (
        'timestamp' => '2013-01-17T00:54:35.063-08:00',
        'ack' => 'Success',
        'correlationId' => '12d859732f93b',
        'build' => '4923149',
      ),
      'payKey' => 'AP-7LK9396428950141K',
      'paymentExecStatus' => 'CREATED',
    )
   *
    Error response example:
   *
    array (
      'responseEnvelope' =>
      array (
        'timestamp' => '2013-01-17T00:55:24.601-08:00',
        'ack' => 'Failure',
        'correlationId' => '3c3cac2ddcc4b',
        'build' => '4923149',
      ),
      'error' =>
      array (
        0 =>
        array (
          'errorId' => '580022',
          'domain' => 'PLATFORM',
          'subdomain' => 'Application',
          'severity' => 'Error',
          'category' => 'Application',
          'message' => 'The system did not recognize the action type PAY-',
          'parameter' =>
          array (
            0 => 'actionType',
          ),
        ),
      ),
     )
   */
  public function pay(PayOptions $options) {

    $data = array(
      "clientDetails" => array(
        "applicationId" => $this->configuration->getAppId(),
        "ipAddress" => "127.0.0.1"
      ),
      "actionType" => $options->getActionType(),
      "currencyCode" => $options->getCurrencyCode(),
      "cancelUrl" => $options->getCancelUrl(),
      "feesPayer" => $options->getFeesPayer(),
      "senderEmail" => $options->getSenderEmail(),
      "ipnNotificationUrl" => $options->getIpnNotificationUrl(),
      "returnUrl" => $options->getReturnUrl(),
      "cancelUrl" => $options->getCancelUrl(),
      "memo" => $options->getMemo(),
      "trackingId" => $options->getTrackingId()
    );

    // receiver list

    $receivers = $options->getReceivers();

    $data["receiverList"] = array();
    $data["receiverList"]["receiver"] = array();

    for ($i = 0; $i < count($receivers); $i++) {

      $data["receiverList"]["receiver"][$i] = array(
        "amount" => round($receivers[$i]->getAmount(), 2),
        "email" => $receivers[$i]->getEmail(),
        "primary" => $receivers[$i]->getIsPrimary(),
        "invoiceId" => $receivers[$i]->getInvoiceId(),
        "paymentType" => $receivers[$i]->getPaymentType()
      );

      // filter nulls
      $data["receiverList"]["receiver"][$i] = array_filter(
        $data["receiverList"]["receiver"][$i], function ($e) {
          return !is_null($e);
        });
    }

    $endpoint = ($this->configuration->getIsSandbox() ?
      self::ENDPOINT_SANDBOX : self::ENDPOINT_PRODUCTION)
      . "Pay";

    return $this->callAPI($endpoint, $data);
  }

  public function paymentDetails($payKey) {
    $data = array("payKey" => $payKey);

    $endpoint = ($this->configuration->getIsSandbox() ?
      self::ENDPOINT_SANDBOX : self::ENDPOINT_PRODUCTION)
      . "PaymentDetails";

    return $this->callAPI($endpoint, $data);
  }

  // <editor-fold defaultstate="collapsed" desc="Accessors">

  public function getConfiguration() {
    return $this->configuration;
  }

  public function setConfiguration($configuration) {
    $this->configuration = $configuration;
  }

  // </editor-fold>
}
