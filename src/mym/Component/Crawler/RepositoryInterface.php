<?php

namespace mym\Component\Crawler;

interface RepositoryInterface
{
  /**
   * Clear data
   */
  public function reset();

  public function set($key, array $value);
  public function get($key);
  public function remove($key);
}
