<?php

/**
 * @copyright 2012, Mikhail Yurasov
 */

namespace mym\Component\PayPal\AdaptivePayments;

class PaymentReceiver {

  private $email = "";
  private $isPrimary = false;
  private $amount = 0.0; // float

  // <editor-fold defaultstate="collapsed" desc="Accessors">

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
