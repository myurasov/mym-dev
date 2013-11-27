<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\Component\Crawler;

use mym\Component\Crawler\Repository\RepositoryInterface;
use mym\Component\Crawler\Processor\ProcessorPool;
use Psr\Log\LoggerAwareInterface;

interface DispatcherInterface extends LoggerAwareInterface
{
  public function run();
  public function getRepository();
  public function setRepository(RepositoryInterface $repository);
}