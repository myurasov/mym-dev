<?php

namespace mym\Component\Crawler;

class Dispatcher
{
  /**
   * @var Url[]
   */
  private $urls = [];

  /**
   * @var MongoRepository
   */
  private $repository;

  /**
   * @var ProcessorPoolAdapterInterface
   */
  private $processorPoolAdapter;

  public function run_()
  {
    foreach ($this->urls as $i => $url /* @var $url Url */) {
      $this->processorPoolAdapter->process($url);
      unset($this->urls[$i]);

      foreach ($this->processorPoolAdapter->getExtractedUrls() as $extractedUrl) {
        $this->repository->save($extractedUrl);
      }

      $this->urls = array_merge(
        $this->urls, $this->processorPoolAdapter->getExtractedUrls()
      );

//      print_r($this->processorPoolAdapter->getExtractedUrls());
      echo "count: ";
      print_r(count($this->urls));
      echo "\nmem: ";
      print_r(memory_get_usage(true)/1024/1024);
      echo "\n\n";
    }

    $this->run();
  }

  public function run()
  {
    while ($url /* @var $url Url */ = $this->repository->next()) {

      echo "url (L{$url->getDepth()}) (left {$this->repository->count()}): ", $url->getUrl(), "\n";

      $this->processorPoolAdapter->process($url);

//      echo "mem: ", memory_get_peak_usage(true) / 1024 / 1024, "\n";

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

  public function setProcessorPoolAdapter(ProcessorPoolAdapterInterface $processorPoolAdapter)
  {
    $this->processorPoolAdapter = $processorPoolAdapter;
  }

  public function getRepository()
  {
    return $this->repository;
  }

  public function setRepository($repository)
  {
    $this->repository = $repository;
  }

  // </editor-fold>
}