<?php

function load($class) {
    $class = str_replace('\\', '/', $class);
    $dir = dirname(__FILE__);
    $file = "$dir/$class.php";
    if(file_exists($file)) {
        require_once($file);
        return true;
    }
}
spl_autoload_register('load');
