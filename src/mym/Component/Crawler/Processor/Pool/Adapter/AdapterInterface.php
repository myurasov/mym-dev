<?php

namespace mym\Component\Crawler\Processor\Pool\Adapter;

use mym\Component\Crawler\Url;

interface AdapterInterface
{
  public function process(Url &$url);
  public function getExtractedUrls();
  public function setExtractedUrls($extractedUrls);
}