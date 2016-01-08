<?php

/**
 * View - load template pages
 *
 */
namespace Core;

/**
 * View class to load template and views files.
 */
class View {

	/**
	 *
	 * @var array Array of HTTP headers
	 */
	private static $headers = [ ];

	/**
	 * Include template file.
	 *
	 * @param string $path
	 *        	path to file from views folder
	 * @param array $data
	 *        	array of data
	 * @param array $error
	 *        	array of errors
	 */
	public static function render($path, $data = false, $error = false) {
		if (! headers_sent()) {
			foreach (self::$headers as $header) {
				header($header, true);
			}
		}
		require SMVC . "app/views/$path.php";
	}

	public static function renderDefault($path, $data = false, $menuToHighlight = []) {
		if (! headers_sent()) {
			foreach (self::$headers as $header) {
				header($header, true);
			}
		}
		
		$data['menu_to_highlight'] = $menuToHighlight;
		
		// render header & footer automatically
		self::renderTemplate('header', $data);
		if (file_exists(SMVC . "app/Modules/$path.php")) // render module view
			require SMVC . "app/Modules/$path.php";
		else
			require SMVC . "app/views/$path.php";
		
		self::renderTemplate('footer', $data);
	}

	/**
	 * Include template file.
	 *
	 * @param string $path
	 *        	path to file from Modules folder
	 * @param array $data
	 *        	array of data
	 * @param array $error
	 *        	array of errors
	 */
	public static function renderModule($path, $data = null, $error = null) {
		if (! headers_sent()) {
			foreach (self::$headers as $header) {
				header($header, true);
			}
		}
		require SMVC . "app/Modules/$path.php";
	}

	/**
	 * Return absolute path to selected template directory.
	 *
	 * @param string $path
	 *        	path to file from views folder
	 * @param array $data
	 *        	array of data
	 * @param string $custom
	 *        	path to template folder
	 */
	public static function renderTemplate($path, $data = false, $custom = false) {
		if (! headers_sent()) {
			foreach (self::$headers as $header) {
				header($header, true);
			}
		}
		
		if ($custom === false) {
			require SMVC . "app/templates/" . TEMPLATE . "/$path.php";
		} else {
			require SMVC . "app/templates/$custom/$path.php";
		}
	}

	/**
	 * Add HTTP header to headers array.
	 *
	 * @param string $header
	 *        	HTTP header text
	 */
	public function addHeader($header) {
		self::$headers[] = $header;
	}

	/**
	 * Add an array with headers to the view.
	 *
	 * @param array $headers        	
	 */
	public function addHeaders($headers = []) {
		foreach ($headers as $header) {
			$this->addHeader($header);
		}
	}
}
