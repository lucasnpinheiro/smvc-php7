<?php

namespace Controllers\SubFolder;

use Core\View;
use Core\Controller;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class NestedController extends Controller
{

    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->locale->load('Welcome');
    }

    /**
     * Define Index page title and load template files
     */
    public function index()
    {
        $data['title'] = $this->locale->get('welcome_text');
        $data['welcome_message'] = $this->locale->get('welcome_message');
        
        View::renderDefault('welcome/welcome', $data);
    }

    
}
