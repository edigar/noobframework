<?php

function load($class) {
    $class = str_replace('\\', '/', $class);
    $dir = dirname(__FILE__);
    $file = "$dir/$class.php";
    if(file_exists($file)) {
        require_once($file);
    }
}

function loadNotFound() {
    header('HTTP/1.1 404 Not Found');
    include 'public/404.html';
    die();
}

spl_autoload_register('load');
spl_autoload_register('loadNotFound');
