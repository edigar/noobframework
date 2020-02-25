<?php

namespace Core;

class Controller {
    
    protected function view($name, $params = null) {
        $varName = $name;
        $$varName = $params;
        return require_once('App/views/' . $name . '.html');
    }
}
