<?php

/*
 * Configuration constants and options.
 * Executed as soon as the framework runs.
 */
/*
 * ---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 * ---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 * development
 * testing
 * production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
define('ENVIRONMENT', 'development');

/*
 * ---------------------------------------------------------------
 * ERROR REPORTING
 * ---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but production will hide them.
 */

if (defined('ENVIRONMENT')) {
    switch (ENVIRONMENT) {
        case 'development':
            error_reporting(E_ALL);
            break;
        case 'production':
            error_reporting(0);
            break;
        default:
            exit('The application environment is not set correctly.');
    }
}

/**
 * Define relative base path.
 */
define('DIR', '/smvc-php7/public/');

/**
 * Set default controller and method for legacy calls.
 */
define('DEFAULT_CONTROLLER', 'welcome');
define('DEFAULT_METHOD', 'index');

/**
 * Set the default template.
 */
define('TEMPLATE', 'default');

/**
 * Set a default language.
 */
define('LANGUAGE_CODE', 'en');

// database details ONLY NEEDED IF USING A DATABASE

/**
 * Database engine default is mysql.
 */
define('DB_TYPE', 'mysql');

/**
 * Database host default is localhost.
 */
define('DB_HOST', 'localhost');

/**
 * Database name.
 */
define('DB_NAME', 'dbname');

/**
 * Database username.
 */
define('DB_USER', 'non_root_user');

/**
 * Database password.
 */
define('DB_PASS', 'password');

/**
 * PREFER to be used in database calls default is smvc_
 */
define('DB_TABLE_PREFIX', 'smvc_');

/**
 * Set prefix for sessions.
 */
define('SESSION_PREFIX', 'smvc_');

/**
 * Optional create a constant for the name of the site.
 */
define('SITE_TITLE', 'SMVC-PHP7');

/**
 * Optionall set a site email address.
 */
// define('SITEEMAIL', '');

/**
 * Set timezone.
 */
date_default_timezone_set('Europe/London');

