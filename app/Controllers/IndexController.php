<?php

namespace App\Controllers;

use Core\Controller;

class IndexController extends Controller {
    
    public function index() {
        return $this->view('index');
    }
}
