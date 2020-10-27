<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class UsersController extends Controller {
    
  public function index() {
    $user = new User();
    $users = $user->findAll();

    $this->view('users', ['users' => $users]);
  }
}