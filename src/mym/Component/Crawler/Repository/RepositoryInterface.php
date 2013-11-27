<?php

namespace mym\Component\Crawler\Repository;

use mym\Component\Crawler\Url;

interface RepositoryInterface
{
  /**
   * Sets processing flag to true
   *
   * @return Url|false
   */
  public function next();

  public function count();

  public function resetProcessing();

  public function clear();

  public function update(Url &$url);

  /**
   * Sets processing flag to false
   */
  public function done(Url &$url);

  public function insert(Url $url);

  public function getMaxDepth();

  public function setMaxDepth($maxDepth);

  public function getMinAgeToReprocess();

  public function setMinAgeToReprocess($minAgeToReprocess);
}
