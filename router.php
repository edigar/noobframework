<?php

$uri = ltrim($_SERVER['REQUEST_URI'], '/');

if(!empty($uri)) {
    $_GET['key'] = $uri;
}

require_once 'index.php';
