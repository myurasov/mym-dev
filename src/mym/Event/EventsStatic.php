<?php

/**
 * Static events trait
 * @copyright 2013, Mikhail Yurasov
 */

namespace mym\Event;

trait EventsStatic {

  protected static $listeners = [];

  public static function addEventListener($eventName, $callable) {
    if (!isset(self::$listeners[$eventName])) {
      self::$listeners[$eventName] = [];
    }

    self::$listeners[$eventName][] = $callable;
  }

  public static function removeEventListener($callable) {
    for ($i = 0; $i < count(self::$listeners); $i++) {
      for ($j = 0; $j < count(self::$listeners[$events[$i]]); $j++) {
        if (self::$listeners[$events[$i]][$j] === $callable) {
          unset(self::$listeners[$events[$i]][$j]);
        }
      }
    }
  }

  public static function fireEvent(Event $event) {
    $name = $event->getName();

    if (empty($name)) {
      throw new \Exception("Event name cannot be empty");
    }

    if (isset(self::$listeners[$name])) {
      $event->setSource(get_called_class());

      for ($i = 0; $i < count(self::$listeners[$name]); $i++) {
        call_user_func(self::$listeners[$name][$i], $event);

        if ($event->getStopPropagation()) {
          break;
        }
      }
    }
  }
}