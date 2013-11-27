<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Component\Crawler;

use mym\Component\Crawler\Url;
use mym\Component\Crawler\Repository\RepositoryInterface;
use mym\Component\Crawler\Processor\ProcessorPool;

use mym\Component\Crawler\DispatcherInterface;

class NativeDispatcher implements DispatcherInterface
{
  /**
   * @var RepositoryInterface
   */
  private $repository;

  /**
   * @var ProcessorPool
   */
  private $processorPool;

  /**
   * @var LoggerInterface
   */
  private $logger;

  public function run()
  {
    while ($url /* @var $url Url */ = $this->repository->next()) {
      $this->processorPool->process($url);

      echo "url: ", $url->getUrl(), "\n";

      foreach ($this->processorPool->getExtractedUrls() as $eu /* @var $eu Url */) {
        $this->repository->insert($eu);
      }

      $this->repository->done($url);
    }
  }

  // <editor-fold defaultstate="collapsed" desc="accessors">

  public function getRepository()
  {
    return $this->repository;
  }

  public function setRepository(RepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function setLogger(LoggerInterface $logger)
  {
    $this->logger = $logger;
  }

  public function getProcessorPool()
  {
    return $this->processorPool;
  }

  public function setProcessorPool(ProcessorPool $processorPool)
  {
    $this->processorPool = $processorPool;
  }

  // </editor-fold>
}