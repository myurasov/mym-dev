<?php

/**
 * Support for geospatial queries
 * @copyright 2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym\ODM\Traits;

trait GeospatialTrait
{
  /**
   * @ODM\EmbedOne(targetDocument="\Fleapop\Document\Location")
   * @ODM\Index(keys={"location"="2d"})
   *
   * @Serializer\Expose
   * 
   * @var \Fleapop\Document\Location
   */
  protected $location;

  /**
   * @ODM\Distance
   * @Serializer\Expose
   */
  protected $distance;

  /**
   * @return \Doctrine\ODM\MongoDB\Query\Builder
   */
  public static function createNearQueryBuilder($latitude, $longitude, $distance, $units)
  {
    $unitsMultiplier = [
      "mi" => 1,
      "km" => 0.621371,
    ];

    if (!isset($unitsMultiplier[$units])) {
      throw new \Exception("Invalid units name");
    }

    $qb = \Fleapop\Helper\DocumentManager::getInstance()->createQueryBuilder(get_called_class())
      ->field('location')->geoNear($longitude, $latitude)
      ->spherical(true)
      ->distanceMultiplier(3963.192 * $unitsMultiplier[$units]);

    if ($distance) {
      $qb->maxDistance($distance / (3963.192 * $unitsMultiplier[$units]));
    }

    return $qb;
  }

  /**
   * Get users located nearby
   * @param float $latitude
   * @param float $longitude
   * @param float $distance
   * @return \Doctrine\ODM\MongoDB\Cursor
   */
  public static function getNear($latitude, $longitude, $distance = false, $units = "mi")
  {
    return self::createNearQueryBuilder($latitude, $longitude, $distance, $units)->getQuery()->execute();
  }

  public function getDistance()
  {
    return $this->distance;
  }

  public function setDistance($distance)
  {
    $this->distance = $distance;
  }

  public function getLocation()
  {
    return $this->location;
  }

  public function setLocation($location)
  {
    $this->location = $location;
  }

}
