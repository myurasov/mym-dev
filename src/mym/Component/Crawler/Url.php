<?php

namespace mym\Component\Crawler;

class Url
{
  private $url = '';
  private $depth = 0;

  public function __construct($url = '', $depth = 0)
  {
    $this->setUrl($url);
    $this->setDepth($depth);
  }

  public function toArray()
  {
    return array_diff_key(get_object_vars($this), [
      // keys to remove
      // key => 1
    ]);
  }

  public function fromArray(array $data)
  {
    $data = array_intersect_key($data, [
      // keys to use
      'url' => 1,
      'depth' => 1
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

  // </editor-fold>
}