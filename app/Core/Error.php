<?php

namespace Core;

/**
 * Error class to generate 404 pages.
 */
class Error extends \Core\Controller {

	/**
	 * $error holder.
	 *
	 * @var string
	 */
	private $error = null;

	/**
	 * Save error to $this->error.
	 *
	 * @param string $error        	
	 */
	public function __construct($error) {
		parent::__construct();
		$this->error = $error;
	}

	/**
	 * Load a 404 page with the error message.
	 */
	public function index() {
		header("HTTP/1.0 404 Not Found");
		
		$data['title'] = '404';
		$data['error'] = $this->error;
		
		\Core\View::renderTemplate('header', $data);
		\Core\View::render('error/404', $data);
		\Core\View::renderTemplate('footer', $data);
	}

	/**
	 * Display errors.
	 *
	 * @param array $error
	 *        	an error of errors
	 * @param string $class
	 *        	name of class to apply to div
	 *        	
	 * @return string return the errors inside divs
	 */
	public static function display($error, $class = 'alert alert-danger') {
		if (is_array($error)) {
			foreach ($error as $error) {
				$row .= "<div class='$class'>$error</div>";
			}
			return $row;
		} else {
			if (isset($error)) {
				return "<div class='$class'>$error</div>";
			}
		}
	}
}
