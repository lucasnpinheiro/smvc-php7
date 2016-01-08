<?php
/**
 * Routes - all standard routes are defined here.
 *
 * @author David Carr - dave@daveismyname.com
 * @version 2.2
 * @date updated Sept 19, 2015
 */

/**
 * Create alias for Router.
 */
use Core\Router, Helpers\Hooks;

/**
 * Define routes.
 */
Router::any('', 'Controllers\Welcome@index');
Router::any('subpage', 'Controllers\Welcome@subPage');

/**
 * If no route found.
 */
Router::error('Core\Error@index');

/**
 * Turn on old style routing.
 */
Router::$fallback = true;

/**
 * Execute matched routes.
 */
Router::dispatch();
