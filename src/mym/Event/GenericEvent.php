<?php

namespace mym\Event;

class GenericEvent extends AbstractEvent implements \ArrayAccess, \IteratorAggregate {

  protected $arguments;

  public function getArgument($key) {
    if ($this->hasArgument($key)) {
      return $this->arguments[$key];
    }

    throw new \InvalidArgumentException();
  }

  public function setArgument($key, $value) {
    $this->arguments[$key] = $value;
  }

  public function getArguments() {
    return $this->arguments;
  }

  public function setArguments(array $args = array()) {
    $this->arguments = $args;
  }

  public function hasArgument($key) {
    return array_key_exists($key, $this->arguments);
  }

  public function offsetGet($key) {
    return $this->getArgument($key);
  }

  public function offsetSet($key, $value) {
    $this->setArgument($key, $value);
  }

  public function offsetUnset($key) {
    if ($this->hasArgument($key)) {
      unset($this->arguments[$key]);
    }
  }

  public function offsetExists($key) {
    return $this->hasArgument($key);
  }

  public function getIterator() {
    return new \ArrayIterator($this->arguments);
  }

}
