<?php


namespace mym\Component\Crawler\Processor\Pool\Adapter;

use mym\Component\Crawler\Processor\Pool\Adapter\AdapterInterface;
use mym\Component\Crawler\Processor\Pool\PoolInterface;
use mym\Component\Crawler\Url;

class NativeAdapter implements AdapterInterface
{
  /**
   * @var PoolInterface
   */
  private $processorPool;

  /**
   * @var Urls[]
   */
  private $extractedUrls = [];

  public function process(Url &$url)
  {
    $this->extractedUrls = [];
    $this->processorPool->process($url);
    $this->extractedUrls = $this->processorPool->getExtractedUrls();
  }

  // <editor-fold defaultstate="collapsed" desc="accessors">

  public function getProcessorPool()
  {
    return $this->processorPool;
  }

  public function setProcessorPool(PoolInterface $processorPool)
  {
    $this->processorPool = $processorPool;
  }

  public function getExtractedUrls()
  {
    return $this->extractedUrls;
  }

  public function setExtractedUrls($extractedUrls)
  {
    $this->extractedUrls = $extractedUrls;
  }

  // </editor-fold>
}