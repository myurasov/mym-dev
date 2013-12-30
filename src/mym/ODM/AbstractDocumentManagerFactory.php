<?php

/**
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\ODM;

class AbstractDocumentManagerFactory
{
  /**
   * @var DocumentManager
   */
  protected static $dm;

  /**
   * @return \Doctrine\ODM\MongoDB\DocumentManager
   */
  public static function get()
  {
    if (!static::$dm) {
      static::create();
    }

    return static::$dm;
  }

  protected static function create()
  {
  }

  public static function persist($document)
  {
    static::get()->persist($document);
  }

  public static function flush($document = null)
  {
    static::get()->flush($document);
  }

  /**
   * @return \MongoClient
   */
  public static function getMongo()
  {
    static::get()->getConnection()->initialize();
    return static::get()->getConnection()->getMongo();
  }

  /**
   * @param string $document
   * @return \MongoCollection
   */
  public static function getDocumentMongoCollection($document)
  {
    return static::get()->getDocumentCollection($document)->getMongoCollection();
  }
}