<?php

$uri = ltrim($_SERVER['REQUEST_URI'], '/');

if(!empty($uri)) {
    if(explode("/", $uri)[0] == "public") {
        return false;
    }
    $_GET['key'] = $uri;
}

require_once 'index.php';
