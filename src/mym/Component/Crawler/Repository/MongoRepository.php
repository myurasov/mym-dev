<?php

namespace mym\Component\Crawler\Repository;

use mym\Component\Crawler\Url;
use mym\Component\Crawler\Repository\RepositoryInterface;

class MongoRepository implements RepositoryInterface
{
  private $server = '';
  private $db = '';
  private $collection = '';

  /**
   * @var \MongoCollection
   */
  private $mongoCollection;

  /**
   * Maximum link depth
   */
  private $maxDepth;

  /**
   * Minumum age of urls to be processes since last update [sec].
   * -1 equals infinity (do not process again).
   */
  private $minAgeToReprocess = -1;

  public function __construct($server = 'mongodb://localhost:27017', $db = 'Crawler', $collection = 'repository')
  {
    $this->server = $server;
    $this->db = $db;
    $this->collection = $collection;

    // connect to the database
    $mongoClient = new \MongoClient($this->server);
    $this->mongoCollection = $mongoClient->selectCollection($this->db, $this->collection);
  }

  private function createIndexes()
  {
    static $indexesCreated = false;

    if (!$indexesCreated) {
      $this->mongoCollection->ensureIndex([
        'url.updatedAt' => 1 /* ASC */
      ]);

      $this->mongoCollection->ensureIndex([
        'url.createdAt' => 1 /* ASC */
      ]);

      $this->mongoCollection->ensureIndex([
        'url.depth' => 1
      ]);

      $this->mongoCollection->ensureIndex([
        'url.status' => 1
      ]);

      $indexesCreated = true;
    }
  }

  private function createNextQuery()
  {
    $query = [
      'url.depth' => ['$lte' => $this->maxDepth],
      'processing' => false,
    ];

    // min age sice last update
    if (-1 === $this->minAgeToReprocess) {
      $query['url.status'] = Url::STATUS_NEW;
    } else {
      $query['$or'] = [
        ['url.updatedAt' => ['$lte' => microtime(true) - $this->minAgeToReprocess]],
        ['url.status' => Url::STATUS_NEW]
      ];
    }

    return $query;
  }

  /**
   * @return Url
   */
  public function next()
  {
    $data = $this->mongoCollection->findAndModify($this->createNextQuery(), [
      '$set' => ['processing' => true]
    ], null, ['sort' => ['url.updatedAt' => 1]]);

    if (!empty($data)) {
      $url = new Url();
      $url->fromArray($data['url']);
      return $url;
    }

    return false;
  }

  public function count()
  {
    return $this->mongoCollection->count($this->createNextQuery());
  }

  public function resetProcessing()
  {
    $this->mongoCollection->update([
      'processing' => true
    ], [
      '$set' => ['processing' => false]
    ], [
      'multiple' => true
    ]);
  }

  public function resetStatus($status)
  {
    $this->mongoCollection->update([
      'url.status' => $status
    ], [
      '$set' => ['url.status' => Url::STATUS_NEW]
    ], [
      'multiple' => true
    ]);
  }

  public function clear()
  {
    $this->mongoCollection->drop();
  }

  public function get($key)
  {
    return $this->mongoCollection->find(['_id' => $key]);
  }

  public function remove($key)
  {
    $this->mongoCollection->remove(['_id' => $key]);
  }

  public function update(Url &$url)
  {
    $url->refreshUpdatedAt();

    $this->mongoCollection->update([
      '_id' => $url->getId(),
    ], [
      'url' => $url->toArray()
    ]);
  }

  /**
   * Mark Url as done
   */
  public function done(Url &$url)
  {
    $url->refreshUpdatedAt();

    $this->mongoCollection->findAndModify([
      '_id' => $url->getId(),
    ], ['$set' => [
      'url' => $url->toArray(),
      'processing' => false
    ]]);
  }

  public function insert(Url $url)
  {
    try {
      $this->mongoCollection->insert([
        '_id' => $url->getId(),
        'processing' => false,
        'url' => $url->toArray()
      ]);
    }
    catch (\MongoCursorException $e) {

      if ($e->getCode() === 11000 /* Duplicate */) {
        return false;
      }

      throw $e;
    }

    $this->createIndexes();

    return true;
  }

  // <editor-fold defaultstate="collapsed" desc="accessors">

  public function setServer($server)
  {
    $this->server = $server;
  }

  public function setDatabase($database)
  {
    $this->database = $database;
  }

  public function setCollection($collection)
  {
    $this->collection = $collection;
  }

  public function getMaxDepth()
  {
    return $this->maxDepth;
  }

  public function setMaxDepth($maxDepth)
  {
    $this->maxDepth = $maxDepth;
  }

  public function getMinAgeToReprocess()
  {
    return $this->minAgeToReprocess;
  }

  public function setMinAgeToReprocess($minAgeToReprocess)
  {
    $this->minAgeToReprocess = $minAgeToReprocess;
  }

  // </editor-fold>
}