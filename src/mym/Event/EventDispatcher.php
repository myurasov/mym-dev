<?php

/**
 * Event dispatcher
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym\Event;

use mym\Singleton;

class EventDispatcher {
  use Singleton, Events;
}