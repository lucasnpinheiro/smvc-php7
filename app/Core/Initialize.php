<?php

namespace Core;

use Helpers\Session;

class Initialize {

	public function __construct() {
		
		/**
		 * check the configuration file presence
		 */
		if (! is_readable(SMVC . 'app/config.php')) {
			die('No Config.php found, configure and rename config.example.php to config.php in app/.');
		}
		
		/**
		 * Turn on output buffering.
		 */
		ob_start();
		
		/**
		 * load config
		 */
		require SMVC . 'app/config.php';
		
		/**
		 * Turn on custom error handling.
		 */
		// set_exception_handler('Core\Logger::ExceptionHandler');
		// set_error_handler('Core\Logger::ErrorHandler');
		
		/**
		 * Start sessions.
		 */
		Session::init();
		
		$this->applicationSpecificInitializations();
		
		/**
		 * load routes and call controller/view finally
		 */
		require SMVC . 'app/routes.php';
	}

	public function applicationSpecificInitializations() {
		
		/**
		 * Application specific initializations
		 */
		define('THIS_USER_ID', \Helpers\Session::get('user_id'));
	}
}
