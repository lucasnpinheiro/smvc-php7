<?php

/**
 * PHP version 7.0 dependancy
 */
if (PHP_VERSION_ID < 70000) {
	echo "<h1>PHP v7.x required !</h1>";
	echo "<p>PHP v7.x is required for this. Visit   <a href='http://www.php.net/'>php.net</a> to get latest PHP version.</p>";
	exit();
}

/**
 * SimpleMVC specifed directory default is '.'
 * If app folder is not in the same directory update it's path
 */
$smvc = '..';

/**
 * Set the full path to the docroot
 */
define('ROOT', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

/**
 * Make the application relative to the docroot, for symlink'd index.php
 */
if (! is_dir($smvc) and is_dir(ROOT . $smvc)) {
	$smvc = ROOT . $smvc;
}

/**
 * Define the absolute paths for configured directories
 */
define('SMVC', realpath($smvc) . DIRECTORY_SEPARATOR);

/**
 * Unset non used variables
 */
unset($smvc);

/**
 * load composer autoloader
 */
if (file_exists(SMVC . 'vendor/autoload.php')) {
	require SMVC . 'vendor/autoload.php';
} else {
	echo "<h1>Please install via composer.json</h1>";
	echo "<p>Install Composer instructions: <a href='https://getcomposer.org/doc/00-intro.md#globally'>https://getcomposer.org/doc/00-intro.md#globally</a></p>";
	echo "<p>Once composer is installed navigate to the working directory in your terminal/command promt and enter 'composer install'</p>";
	exit();
}

/**
 * initiate
 */
new Core\Initialize();

