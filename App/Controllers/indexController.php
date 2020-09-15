<?php
namespace App\Controllers;

use Core\Controller;

class IndexController extends Controller {
    
    public function index() {
        // You will need to enter the file name with the extension.
        $this->view('index.html');
    }
}