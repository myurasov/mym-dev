<?php

/**
 * @copyright 2012, Mikhail Yurasov
 */

namespace mym\Component\PayPal\AdaptivePayments;

class PayOptions {

  private $senderEmail = "";
  private $receivers = array();
  private $currencyCode = "USD";
  private $cancelUrl = "";
  private $returnUrl = ""; // url user is redirected to after payment

  // <editor-fold defaultstate="collapsed" desc="Accessors">

  public function getSenderEmail() {
    return $this->senderEmail;
  }

  public function setSenderEmail($senderEmail) {
    $this->senderEmail = $senderEmail;
  }

  public function getReceivers() {
    return $this->receivers;
  }

  public function setReceivers($receivers) {
    $this->receivers = $receivers;
  }

  public function getCurrencyCode() {
    return $this->currencyCode;
  }

  public function setCurrencyCode($currencyCode) {
    $this->currencyCode = $currencyCode;
  }

  public function getCancelUrl() {
    return $this->cancelUrl;
  }

  public function setCancelUrl($cancelUrl) {
    $this->cancelUrl = $cancelUrl;
  }

  public function getReturnUrl() {
    return $this->returnUrl;
  }

  public function setReturnUrl($returnUrl) {
    $this->returnUrl = $returnUrl;
  }

  // </editor-fold>
}