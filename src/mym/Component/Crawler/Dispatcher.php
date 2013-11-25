<?php

namespace mym\Component\Crawler;

use mym\Component\Crawler\Repository\RepositoryInterface;
use mym\Component\Crawler\Processor\Pool\Adapter\AdapterInterface;
use mym\Component\Crawler\Url;

class Dispatcher
{
  /**
   * @var Url[]
   */
  private $urls = [];

  /**
   * @var RepositoryInterface
   */
  private $repository;

  /**
   * @var AdapterInterface
   */
  private $processorPoolAdapter;

  public function run()
  {
    while ($url /* @var $url Url */ = $this->repository->next()) {

      echo "url (L{$url->getDepth()}) (left {$this->repository->count()}): ", $url->getUrl(), "\n";

      $this->processorPoolAdapter->process($url);

      foreach ($this->processorPoolAdapter->getExtractedUrls() as $extractedUrl) {
        $this->repository->insert($extractedUrl);
      }

      $this->repository->done($url);
    }
  }

  // <editor-fold defaultstate="collapsed" desc="accessors">

  public function getUrls()
  {
    return $this->urls;
  }

  public function setUrls($urls)
  {
    $this->urls = $urls;
  }

  public function getProcessorPoolAdapter()
  {
    return $this->processorPoolAdapter;
  }

  public function setProcessorPoolAdapter(AdapterInterface $processorPoolAdapter)
  {
    $this->processorPoolAdapter = $processorPoolAdapter;
  }

  public function getRepository()
  {
    return $this->repository;
  }

  public function setRepository(RepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  // </editor-fold>
}