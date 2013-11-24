<?php

namespace mym\Component\Crawler;

interface ProcessorPoolAdapterInterface
{
  public function process(Url $url);
  public function getExtractedUrls();
  public function setExtractedUrls($extractedUrls);
}