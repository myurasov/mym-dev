<?php

namespace mym\Component\TwigService;

abstract class TwigService
{
  /**
   * @var \Twig_Environment
   */
  protected static $twigEnviroment;

  protected static $enviroment;
  protected static $cacheDir;
  protected static $templatesDir;

  /**
   * @return \Twig_Environment
   */
  public static function getTwigEnviroment()
  {
    if (is_null(self::$twigEnviroment)) {
      static::configureTwig();
    }

    return self::$twigEnviroment;
  }

  public static function get()
  {
    return self::getTwigEnviroment();
  }

  /**
   * Loads Twig libary and registers it's class autoloader
   */
  protected static function configureTwig()
  {
    $loader = new \Twig_Loader_Filesystem(self::$templatesDir);

    self::$twigEnviroment = new \Twig_Environment($loader, [
      'debug' => self::$enviroment == 'dev' ? true : false,
      'charset' => 'UTF-8',
      'base_template_class' => 'Twig_Template',
      'strict_variables' => false,
      'autoescape' => 'html',
      'cache' => self::$cacheDir,
      'auto_reload' => null,
      'optimizations' => -1,
    ]);

    // debug extension
    if (self::$enviroment == 'dev') {
      self::$twigEnviroment->addExtension(new \Twig_Extension_Debug());
    }
  }
}