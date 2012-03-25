<?php

/**
 * Doctrine 2 ORM helper service
 * Requires DOctrine 2.0 library, downloaded from
 * www.doctrine-project.org
 *
 * @uses Doctrine ORM 2.0
 * @copyright 2011 Misha Yurasov
 * @package ymF
 */

namespace ymF\Helper;

class Doctrine2Helper
{
  /**
   * @var \Doctrine\ORM\EntityManager
   */
  private static $entityManager = null;

  // Is Doctrine autoloading registered?
  private static $autoloadRegistered = false;

  /**
   * Get Doctrine EntityManager
   *
   * @return \Doctrine\ORM\EntityManager
   */
  public static function getEntityManager()
  {
    if (is_null(self::$entityManager))
      self::_createEntityManager();

    return self::$entityManager;
  }

  /**
   * Load Doctrine
   */
  private static function _createEntityManager()
  {
    // Get options

    $options = Config::$options['Doctrine2Helper'];
    $configOptions = $options['configuration'];
    $connectOptions = $options['connection'];

    // Register Doctrine classes autoloading
    self::registerDoctrineAutoload();

    // Register entities and proxies namespaces autoloading

    $classLoader = new \Doctrine\Common\ClassLoader(
      $configOptions['proxiesNamespace'],
      $configOptions['proxiesDir']);
    $classLoader->register();

    $classLoader = new \Doctrine\Common\ClassLoader('TestApp\Entities',
      \ymF\PATH_MODULES . '/TestApp/Entities');
    $classLoader->register();

    // Configure Doctrine

    $config = new \Doctrine\ORM\Configuration();

    // SQL logger

    if (!is_null($configOptions['sqlLoggerClass']))
    {
      if ($configOptions['sqlLoggerClass'] instanceof \Closure)
        $config->setSQLLogger($configOptions['sqlLoggerClass']());
      else
        $config->setSQLLogger(new $configOptions['sqlLoggerClass']);
    }

    // Proxies
    $config->setProxyDir($configOptions['proxiesDir']);
    $config->setProxyNamespace($configOptions['proxiesNamespace']);
    $config->setAutoGenerateProxyClasses($configOptions['proxiesAutoGeneration']);

    // Metadata driver

    if ($configOptions['metadataDriverClass'] instanceof \Closure)
      $config->setMetadataDriverImpl($configOptions['metadataDriverClass']());
    else
      $config->setMetadataDriverImpl(
        new $configOptions['metadataDriverClass']($configOptions['metadataDir']));

    // Caching of metadata & queries

    if ($configOptions['cacheClass'] instanceof \Closure)
      $cache = $configOptions['cacheClass']();
    else
      $cache = new $configOptions['cacheClass'];

    $config->setMetadataCacheImpl($cache);
    $config->setQueryCacheImpl($cache);

    // Create EntityManager

    self::$entityManager = \Doctrine\ORM\EntityManager::create(
      $connectOptions, $config, new \Doctrine\Common\EventManager());
  }

  /**
   * Registers autoloader for Doctrine classes
   */
  public static function registerDoctrineAutoload()
  {
    if (!self::$autoloadRegistered)
    {
      // Include Doctrine ClassLoader
      $libraryPath = \ymF\Config::$options['libraries']['Doctrine2'];
      require $libraryPath . '/Doctrine/Common/ClassLoader.php';

      // Register autoloader
      $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', $libraryPath);
      $classLoader->register();

      // Symfony components, required for doctrine
      $classLoader = new \Doctrine\Common\ClassLoader(
        'Symfony', $libraryPath . '/Doctrine');
      $classLoader->register();

      self::$autoloadRegistered = true;
    }
  }
}