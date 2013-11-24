<?php

namespace mym\Component\Crawler;

use mym\Component\Crawler\ProcessorPoolAdapterInterface;
use mym\Component\Crawler\ProcessorPoolInterface;
use mym\Component\Crawler\Url;

class NativeProcessorPoolAdapter implements ProcessorPoolAdapterInterface
{
  /**
   * @var ProcessorPoolInterface
   */
  private $processorPool;

  /**
   * @var Urls[]
   */
  private $extractedUrls = [];

  public function process(Url $url)
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

  public function setProcessorPool(ProcessorPoolInterface $processorPool)
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