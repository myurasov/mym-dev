<?php

namespace mym\Helper;

use FirePHP;
use mym\Kernel;

class FirePHPHelper
{
  private static $firePhp;

  /**
   * Get FirePHP instance
   *
   * @return FirePHP
   */
  public static function getFirePhp()
  {
    if (\is_null(self::$firePhp))
    {
      require_once \mym\Config::$options['libraries']['FirePHP'] .
        '/lib/FirePHPCore/FirePHP.class.php';

      self::$firePhp = FirePHP::getInstance(true);
    }

    return self::$firePhp;
  }
}