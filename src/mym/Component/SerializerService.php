<?php

/**
 * Serializer service
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 * Uses JMSSerializer
 */

namespace mym\Component;

class SerializerService
{
  /**
   * @var \JMS\Serializer\Serializer
   */
  private $serializer;

  private $format = 'json';
  private $cacheDir;
  private $debug = false;
  private $jsonOptions = JSON_PRETTY_PRINT;


  public function __construct()
  {
  }

  private function createSerializer()
  {
    $sb /* @var $sb \JMS\Serializer\SerializerBuilder  */ =
      \JMS\Serializer\SerializerBuilder::create();

    // set format
    if ($this->format == "json") {

      $sv = new \JMS\Serializer\JsonSerializationVisitor(
        new \JMS\Serializer\Naming\IdenticalPropertyNamingStrategy()
      );

      $sv->setOptions($this->jsonOptions);
      $sb->setSerializationVisitor("json", $sv);
    }
    else if ($this->format == "xml") {

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

  public function serialize($data)
  {
    return $this->getSerializer()->serialize($data, $this->getFormat());
  }

  public function getSerializer()
  {
    if (!$this->serializer) {
      $this->createSerializer();
    }

    return $this->serializer;
  }

  public function setSerializer($serializer)
  {
    $this->serializer = $serializer;
  }

  public function getFormat()
  {
    return $this->format;
  }

  public function setFormat($format)
  {
    $this->format = $format;
  }

  public function getCacheDir()
  {
    return $this->cacheDir;
  }

  public function setCacheDir($cacheDir)
  {
    $this->cacheDir = $cacheDir;
  }

  public function getDebug()
  {
    return $this->debug;
  }

  public function setDebug($debug)
  {
    $this->debug = $debug;
  }

  public function getJsonOptions()
  {
    return $this->jsonOptions;
  }

  public function setJsonOptions($jsonOptions)
  {
    $this->jsonOptions = $jsonOptions;
  }
}