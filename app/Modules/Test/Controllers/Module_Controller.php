<?php

namespace Modules\Test\Controllers;

use Core\View;

class Module_Controller extends \Core\Controller {

	public function __construct() {
		parent::__construct();
		$this->locale->load('Welcome');
		
		// Model can be initialized in constructur
		// $this->model = new \Modules\Test\Models\SampleModelInModule();
	}

	public function index() {
		$data['title'] = $this->locale->get('modulepage_text');
		$data['modulepage_message'] = $this->locale->get('modulepage_message');
		
		View::renderDefault('Test/views/module-controller/index', $data);
	}
}
