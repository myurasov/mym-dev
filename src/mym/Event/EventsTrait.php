<?php

/**
 * Events trait
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym\Event;

use mym\Event\AbstractEvent;

trait EventsTrait {

  protected $listeners = [];

  public function addEventListener($eventName, $callable) {
    if (!isset($this->listeners[$eventName])) {
      $this->listeners[$eventName] = [];
    }

    $this->listeners[$eventName][] = $callable;
  }

  public function removeEventListener($callable) {
    for ($i = 0; $i < count($this->listeners); $i++) {
      for ($j = 0; $j < count($this->listeners[$events[$i]]); $j++) {
        if ($this->listeners[$events[$i]][$j] === $callable) {
          unset($this->listeners[$events[$i]][$j]);
        }
      }
    }
  }

  public function fireEvent(AbstractEvent $event) {
    $name = $event::NAME;

    if (empty($name)) {
      throw new \Exception("Event name cannot be empty");
    }

    if (isset($this->listeners[$name])) {
      $event->setSource($this);

      for ($i = 0; $i < count($this->listeners[$name]); $i++) {
        call_user_func($this->listeners[$name][$i], $event);

        if ($event->getStopPropagation()) {
          break;
        }
      }
    }
  }
}