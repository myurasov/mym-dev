<?php

/**
 * Options for "Pay" action
 *
 * @see https://www.x.com/developers/paypal/documentation-tools/api/Pay-api-operation
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym\Component\PayPal\AdaptivePayments;

class PayOptions {

  const FEESPAYER_SENDER = "SENDER";
  const FEESPAYER_PRIMARYRECEIVER = "PRIMARYRECEIVER";
  const FEESPAYER_EACHRECEIVER = "EACHRECEIVER";
  const FEESPAYER_SECONDARYONLY = "SECONDARYONLY";

  const ACTIONTYPE_PAY = "PAY";
  const ACTIONTYPE_CREATE = "CREATE";
  const ACTIONTYPE_PAY_PRIMARY = "PAY_PRIMARY";

  private $senderEmail = null;
  private $receivers = array();
  private $currencyCode = "USD";
  private $cancelUrl = null;
  private $returnUrl = null;
  private $feesPayer = self::FEESPAYER_EACHRECEIVER;
  private $actionType = self::ACTIONTYPE_PAY;
  private $ipnNotificationUrl = null;
  private $memo = null;
  private $reverseAllParallelPaymentsOnError = false;
  private $trackingId = null;

  // <editor-fold defaultstate="collapsed" desc="Accessors">

  public function getMemo() {
    return $this->memo;
  }

  public function setMemo($memo) {
    $this->memo = $memo;
  }

  public function getReverseAllParallelPaymentsOnError() {
    return $this->reverseAllParallelPaymentsOnError;
  }

  public function setReverseAllParallelPaymentsOnError($reverseAllParallelPaymentsOnError) {
    $this->reverseAllParallelPaymentsOnError = $reverseAllParallelPaymentsOnError;
  }

  public function getTrackingId() {
    return $this->trackingId;
  }

  public function setTrackingId($trackingId) {
    $this->trackingId = $trackingId;
  }

  public function getIpnNotificationUrl() {
    return $this->ipnNotificationUrl;
  }

  public function setIpnNotificationUrl($ipnNotificationUrl) {
    $this->ipnNotificationUrl = $ipnNotificationUrl;
  }

  public function getActionType() {
    return $this->actionType;
  }

  public function setActionType($actionType) {
    $this->actionType = $actionType;
  }

  public function getFeesPayer() {
    return $this->feesPayer;
  }

  public function setFeesPayer($feesPayer) {
    $this->feesPayer = $feesPayer;
  }

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