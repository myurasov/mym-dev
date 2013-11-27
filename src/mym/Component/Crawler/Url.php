<?php

namespace mym\Component\Crawler;

class Url
{
  const STATUS_NEW = 'new';
  const STATUS_OK = 'ok';
  const STATUS_ERROR = 'error';

  private $url = '';
  private $depth = 0;
  private $status = self::STATUS_NEW;

  private $createdAt;
  private $updatedAt = null;

  public function __construct($url = '', $depth = 0)
  {
    $this->createdAt = $this->updatedAt = microtime(true);
    $this->setUrl($url);
    $this->setDepth($depth);
  }

  public function toArray()
  {
    return array_intersect_key(get_object_vars($this), [
      // keys to expose
      'url' => 1,
      'depth' => 1,
      'status' => 1,
      'createdAt' => 1,
      'updatedAt' => 1
    ]);
  }

  public function fromArray(array $data)
  {
    $data = array_intersect_key($data, [
      // keys to use
      'url' => 1,
      'depth' => 1,
      'status' => 1,
      'createdAt' => 1,
      'updatedAt' => 1
    ]);

    foreach ($data as $k => $v) {
      $this->$k = $v;
    }
  }

  /**
   * Get url unique id
   * @return string
   */
  public function getId()
  {
    return md5($this->url);
  }

  public function refreshUpdatedAt()
  {
    $this->updatedAt = microtime(true);
  }

  // <editor-fold defaultstate="collapsed" desc="accessors">

  public function getUrl()
  {
    return $this->url;
  }

  public function setUrl($url)
  {
    $this->url = $url;
  }

  public function getDepth()
  {
    return $this->depth;
  }

  public function setDepth($depth)
  {
    $this->depth = $depth;
  }

  public function getStatus()
  {
    return $this->status;
  }

  public function setStatus($status)
  {
    $this->status = $status;
  }

  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;
  }

  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt($updatedAt)
  {
    $this->updatedAt = $updatedAt;
  }

  // </editor-fold>
}