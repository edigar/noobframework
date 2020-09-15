<?php

namespace Core;

class Controller {
    
    protected function view($name, $params = null) {
        $varName = $name;
        $$varName = $params;
        /* This variable takes the entire file name and turns into a 
        reverse array every space of the array is separated by a period "." */
        $extension = array_reverse(explode('.', $name));

        if ($extension[0] == 'html' or $extension[0] == 'php' or $extension[0] == 'phtml'){
            return require_once('App/views/' . $name);
        } else {
            echo 'NOTICE: supported files are HTML, PHP and PHTML';
        }
    }
}
