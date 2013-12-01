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

  /**
   * @return \MongoClient
   */
  public static function getMongoClient()
  {
    $dm = static::get();
    $dm->getConnection()->initialize();
    return $dm->getConnection()->getMongo()->selectDB($dm->getConfiguration()->getDefaultDB());
  }

  public static function persist($document)
  {
    static::get()->persist($document);
  }

  public static function flush($document = null)
  {
    static::get()->flush($document);
  }
}