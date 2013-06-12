<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Component\REST;

use \Symfony\Component\HttpFoundation\Response;
use \mym\Component\SerializerService;

class RESTResponse extends Response
{
  private $format;
  private $data;
  private $serializer;

  private $cacheDir;
  private $debug = false;

  public function __construct($data = null, $format = "json") {
    parent::__construct();
    $this->data = $data;
    $this->setFormat($format);
  }

  /**
   * @return \JMS\Serializer\Serializer
   */
  public function getSerializer()
  {
    if (!$this->serializer) {

      $serializerService = new SerializerService();
      $serializerService->setFormat($this->format);
      $serializerService->setCacheDir($this->cacheDir);
      $serializerService->setDebug($this->debug);
      $this->serializer = $serializerService->getSerializer();
    }

    return $this->serializer;
  }

  public function setFormat($format) {
    $this->format = $format;

    if ($format == "json") {
      $this->headers->set("Content-type", "application/json");
    } else if ($format == "xml") {
      $this->headers->set("Content-type", "text/xml");
    } else {
      throw new \Exception("Format '$format' is not supported");
    }
  }

  public function setData($data) {
    $this->data = $data;
    $this->setContent($this->getSerializer()
      ->serialize($this->data, $this->format));
  }

  public function getFormat() {
    return $this->format;
  }

  public function getData() {
    return $this->data;
  }

  public function getCacheDir() {
    return $this->cacheDir;
  }

  public function setCacheDir($cacheDir) {
    $this->cacheDir = $cacheDir;
  }

  public function getDebug() {
    return $this->debug;
  }

  public function setDebug($debug) {
    $this->debug = $debug;
  }

}