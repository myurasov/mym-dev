<?php

/**
 * Bootstrap
 *
 * @copyright 2012 Mikhail Yurasov
 * @package GeekBase
 */

// Definitions
define('ymF\DEVELOPMENT', (bool) get_cfg_var('development'));
define('ymF\PROJECT_NAME', 'ProjectName');
define('ymF\PROJECT_VERSION', '1.0');
define('ymF\PATH_ROOT', realpath(__DIR__ . '/../..'));

// ymF
require '/Projects/Web/ymF/src/ymF/ymF.php';

// Autoloading

ymF\Kernel::registerAutoloadNamespace('ymF',
  '/Projects/Web/ymF/src');

ymF\Kernel::registerAutoloadNamespace('Symfony\Component',
  ymF\Kernel::getLibraryPath('SymfonyComponent'));

ymF\Kernel::registerAutoloadNamespace(\ymF\PROJECT_NAME,
  \ymF\PATH_MODULES, true);