<?php

/**
 * Event object
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym\Event;

class Event
{
  const NAME = 'event';

  protected $source = null;
  protected $stopPropagation = false;

  public function getSource()
  {
    return $this->source;
  }

  public function setSource($source)
  {
    $this->source = $source;
  }

  public function getStopPropagation()
  {
    return $this->stopPropagation;
  }

  public function setStopPropagation($stopPropagation)
  {
    $this->stopPropagation = $stopPropagation;
  }
}
