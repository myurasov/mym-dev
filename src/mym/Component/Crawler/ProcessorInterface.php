<?php

namespace mym\Component\Crawler;

interface ProcessorInterface
{
  /**
   * @param Url $url Url to handle
   * @return boolean False if Url cannot be processed
   */
  public function process(Url $url);
  public function getExtractedUrls();
  public function setExtractedUrls($extractedUrls);
}