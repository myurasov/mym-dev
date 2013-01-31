<?php

/**
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym\Component\PayPal;

class Configuration {

  const SANDBOX_APP_ID = "APP-80W284485P519543T";

  private $appId = "";
  private $userId = "";
  private $password = "";
  private $signature = "";
  private $isSandbox = false;

  // <editor-fold defaultstate="collapsed" desc="Accessors">

  public function getAppId() {
    return $this->appId;
  }

  public function setAppId($appId) {
    $this->appId = $appId;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function setUserId($userId) {
    $this->userId = $userId;
  }

  public function getPassword() {
    return $this->password;
  }

  public function setPassword($password) {
    $this->password = $password;
  }

  public function getSignature() {
    return $this->signature;
  }

  public function setSignature($signature) {
    $this->signature = $signature;
  }

  public function getIsSandbox() {
    return $this->isSandbox;
  }

  public function setIsSandbox($isSandbox) {
    $this->isSandbox = $isSandbox;
  }
  
  // </editor-fold>
}