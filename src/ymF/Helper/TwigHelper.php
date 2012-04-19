<?php

/**
 * Twig service
 *
 * Requires Twig library, version 0.9.3+,
 * downloaded from www.twig-project.org
 *
 * @copyright 2010-2012 Misha Yurasov
 * @package ymF
 */

namespace ymF\Helper;

class TwigHelper
{
  /**
   * @var \Twig_Environment
   */
  private static $twigEnv;

  /**
   * Loads Twig libary and registers it's class autoloader
   *
   * @staticvar boolean $loaded
   */
  public static function loadTwig()
  {
    if (!class_exists('Twig_Autoloader', false))
    {
      // Load Twig library
      require \ymF\Config::$options['libraries']['Twig']
        . '/lib/Twig/Autoloader.php';

      // Register twig autoloader
      \Twig_Autoloader::register();
    }
  }

  /**
   * @return \Twig_Environment
   */
  public static function getTwigEnviroment()
  {
    if (is_null(self::$twigEnv))
    {
      // Load Twig libary
      self::loadTwig();

      // Create Twig
      self::_createTwigEnviroment();
    }

    return self::$twigEnv;
  }

  /**
   * Get Twig templates directory path
   *
   * @return string
   */
  public static function getTemplatesDir()
  {
    return Config::$options['TwigHelper']['templatesDir'];
  }

  /**
   * Create Twig_Enviroment instance
   */
  protected static function _createTwigEnviroment()
  {
    // Load Twig libary
    self::loadTwig();

    // Configure and create Twig

    $config = Config::$options['TwigHelper'];
    $loader = new \Twig_Loader_Filesystem($config['templatesDir']);
    self::$twigEnv = new \Twig_Environment($loader, $config['enviromentOptions']);

    // Load global vars

    // TODO: load additional extensions from the list
  }

  /**
   * Delete Twig instance
   */
  public static function reset()
  {
    self::$twigEnv = null;
  }

  /**
   * Prevent creation of class instance
   */
  final private function __construct() {}
}