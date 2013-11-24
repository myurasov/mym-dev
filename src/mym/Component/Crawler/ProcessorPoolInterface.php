<?php

namespace mym\Component\Crawler;

interface ProcessorPoolInterface
{
  public function process(Url $url);
  public function addProcessor(ProcessorInterface $processor);
  public function getProcessors();
  public function setProcessors(ProcessorInterface $processors);
  public function getExtractedUrls();
  public function setExtractedUrls($extractedUrls);
}