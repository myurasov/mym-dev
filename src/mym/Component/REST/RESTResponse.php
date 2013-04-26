<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Component\REST;

use \Symfony\Component\HttpFoundation\Response;

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

      $sb /* @var $sb \JMS\Serializer\SerializerBuilder  */ =
        \JMS\Serializer\SerializerBuilder::create();

      // set format
      if ($this->format == "json") {

        $sv =  new \JMS\Serializer\JsonSerializationVisitor(
          new \JMS\Serializer\Naming\IdenticalPropertyNamingStrategy()
        );

        $sv->setOptions(JSON_PRETTY_PRINT);
        $sb->setSerializationVisitor("json", $sv);

      } else if ($this->format == "xml") {

        $sb->setSerializationVisitor("xml", new \JMS\Serializer\XmlSerializationVisitor(
          new \JMS\Serializer\Naming\IdenticalPropertyNamingStrategy()
        ));
      }

      // chache dir
      if ($this->cacheDir) {
        $sb->setCacheDir($this->cacheDir);
      }

      // debugging
      $sb->setDebug($this->debug);

      $this->serializer = $sb->build();
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
    $this->setContent($this->getSerializer()->serialize($this->data, $this->format));
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