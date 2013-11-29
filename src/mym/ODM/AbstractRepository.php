<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\ODM;

use Doctrine\ODM\MongoDB\DocumentRepository;

class AbstractRepository extends DocumentRepository
{
  protected $limit;
  protected $skip = 0;

  protected function createSearchQueryBuilder()
  {
    return $this->createQueryBuilder()
      ->limit($this->limit)
      ->skip($this->skip);
  }

  public function searchByIds($ids = [])
  {
    return $this->createSearchQueryBuilder()
      ->field('id')->in($ids)
      ->getQuery()->execute();
  }

  public function searchAll()
  {
    return $this->createSearchQueryBuilder()
      ->getQuery()->execute();
  }

  public function getLimit()
  {
    return $this->limit;
  }

  public function setLimit($limit)
  {
    $this->limit = $limit;
  }

  public function getSkip()
  {
    return $this->skip;
  }

  public function setSkip($skip)
  {
    $this->skip = $skip;
  }
}
