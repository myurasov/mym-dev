<?php

namespace ymF\Helper;

use FirePHP;
use ymF\Kernel;

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
      require_once Kernel::getLibraryPath('FirePHP') .
        '/lib/FirePHPCore/FirePHP.class.php';

      self::$firePhp = FirePHP::getInstance(true);
    }

    return self::$firePhp;
  }
}