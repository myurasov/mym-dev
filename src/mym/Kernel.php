<?php

/**
 * mym framework
 * @copyright 2009-2013, Mikhail Yurasov <me@yurasov.me>
 */

namespace mym;

class Kernel
{
  /**
   * Initialize mym
   */
  public static function init()
  {
    define('mym\VERSION', '1.2-dev');
    define('mym\ERROR_OK', 0);
    define('mym\ERROR_MISC', -1);
  }
}

Kernel::init();