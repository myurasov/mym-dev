<?php

/**
 * Payment receiver object
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym\Component\PayPal\AdaptivePayments;

class PaymentReceiver {

  const PAYMENT_TYPE_GOODS = "GOODS";
  const PAYMENT_TYPE_SERVICE = "SERVICE";
  const PAYMENT_TYPE_PERSONAL = "PERSONAL";
  const PAYMENT_TYPE_CASHADVANCE = "CASHADVANCE";
  const PAYMENT_TYPE_DIGITALGOODS = "DIGITALGOODS";
  const PAYMENT_TYPE_BANK_MANAGED_WITHDRAWAL = "BANK_MANAGED_WITHDRAWAL";

  private $email = "";
  private $isPrimary = false;
  private $amount = 0.0; // float
  private $invoiceId = null;
  private $paymentType = self::PAYMENT_TYPE_GOODS;

  // <editor-fold defaultstate="collapsed" desc="Accessors">
  
  public function getPaymentType() {
    return $this->paymentType;
  }

  public function setPaymentType($paymentType) {
    $this->paymentType = $paymentType;
  }

  public function getInvoiceId() {
    return $this->invoiceId;
  }

  public function setInvoiceId($invoiceId) {
    $this->invoiceId = $invoiceId;
  }

  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public function getIsPrimary() {
    return $this->isPrimary;
  }

  public function setIsPrimary($isPrimary) {
    $this->isPrimary = $isPrimary;
  }

  public function getAmount() {
    return $this->amount;
  }

  public function setAmount($amount) {
    $this->amount = $amount;
  }

  // </editor-fold>
}
