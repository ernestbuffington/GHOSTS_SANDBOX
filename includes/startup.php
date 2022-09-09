<?php
/**
*
* This file is part of the PHP-Nuke Titanium CMS Software package.
*
* @copyright (c) Brandon Maintenance Managemnt, LLC 
* The 86it Developers Network <https://www.86it.us>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

/**
*/

// Report all errors, except notices and deprecation messages
$level = E_ALL & ~E_NOTICE & ~E_DEPRECATED;
error_reporting($level);

/**
* Minimum Requirement: PHP 7.1.3
*/
if (version_compare(PHP_VERSION, '7.1.3', '<'))
{
	die('You are running an unsupported PHP version. Please upgrade to PHP 7.1.3 or higher before trying to install or update to PHP-Nuke Titanium 4.0.1b');
}
// Register globals and magic quotes have been dropped in PHP 5.4 so no need for extra checks


// In PHP 5.3.0 the error level has been raised to E_WARNING which causes problems
// because we show E_WARNING errors and do not set a default timezone.
// This is because we have our own timezone handling and work in UTC only anyway.

// So what we basically want to do is set our timezone to UTC,
// but we don't know what other scripts (such as bridges) are involved,
// so we check whether a timezone is already set by calling date_default_timezone_get().

// Unfortunately, date_default_timezone_get() itself might throw E_WARNING
// if no timezone has been set, so we have to keep it quiet with @.

// date_default_timezone_get() tries to guess the correct timezone first
// and then falls back to UTC when everything fails.
// We just set the timezone to whatever date_default_timezone_get() returns.
date_default_timezone_set(@date_default_timezone_get());

// Autoloading of dependencies.
// Three options are supported:
// 1. If dependencies are installed with Composer, Composer will create a
//    vendor/autoload.php. If this file exists it will be
//    automatically used by PHP-Nuke Titanium. This is the default mode that
//    Titanium will use when shipped.
// 2. To disable composer autoloading, PHP_NUKE_TITANIUM_NO_COMPOSER_AUTOLOAD 
// 	  can be specified. 
//    Additionally specify PHP_NUKE_TITANIUM_AUTOLOAD=/path/to/autoload.php 
//    in the environment. This is useful for running CLI scripts and tests.
//    /path/to/autoload.php should define and register class loaders
//    for all of PHP-Nuke Titanium's dependencies.
// 3. You can also set PHP_NUKE_TITANIUM_NO_COMPOSER_AUTOLOAD without setting 
//    PHP_NUKE_TITANIUM__AUTOLOAD.
//    In this case autoloading needs to be defined before running any 
//    PHP-Nuke Titanium scripts. This might be useful in cases when 
//    PHP-Nuke Titanium is integrated into a
//    larger program.
if (getenv('PHP_NUKE_TITANIUM_NO_COMPOSER_AUTOLOAD'))
{
	if (getenv('PHP_NUKE_TITANIUM_AUTOLOAD'))
	{
		require(getenv('PHP_NUKE_TITANIUM_AUTOLOAD'));
	}
}
else
{
	if (!file_exists(NUKE_BASE_DIR . 'vendor/autoload.php'))
	{
		trigger_error(
			'Composer dependencies have not been set up yet, run ' .
			"'php ../composer.phar install' from the / directory to do so.",
			E_USER_ERROR
		);
	}
	require(NUKE_BASE_DIR . 'vendor/autoload.php');
}

$starttime = microtime(true);
