<?php

namespace mym\Component\Crawler\Processor;

use mym\Component\Crawler\Processor\ProcessorInterface;
use mym\Component\Crawler\Url;

class ProcessorPool
{
  /**
   * @var ProcessorInterface[]
   */
  private $processors = [];

  /**
   * @var Url[]
   */
  private $extractedUrls = [];

  public function process(Url &$url)
  {
    $this->extractedUrls = [];

    foreach ($this->processors as $processor /* @var $processor ProcessorInterface */) {

      if ($processor->process($url)) {

        $this->extractedUrls = array_merge(
          $this->extractedUrls,
          $processor->getExtractedUrls()
        );

        break;
      }

    }
  }

  public function addProcessor(ProcessorInterface $processor)
  {
    $this->processors[] = $processor;
  }

  // <editor-fold defaultstate="collapsed" desc="accessors">

  public function getProcessors()
  {
    return $this->processors;
  }

  public function setProcessors(ProcessorInterface $processors)
  {
    $this->processors = $processors;
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