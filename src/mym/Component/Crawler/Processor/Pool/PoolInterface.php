<?php

namespace mym\Component\Crawler\Processor\Pool;

use mym\Component\Crawler\Url;
use mym\Component\Crawler\Processor\ProcessorInterface;

/**
 * Processor pool interface
 */
interface PoolInterface
{
  public function process(Url &$url);
  public function addProcessor(ProcessorInterface $processor);
  public function getProcessors();
  public function setProcessors(ProcessorInterface $processors);
  public function getExtractedUrls();
  public function setExtractedUrls($extractedUrls);
}