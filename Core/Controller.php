<?php

namespace Core;

class Controller {
    
    protected function view($name, $params = null) {
        $varName = $name;
        $$varName = $params;
        $ext = ['.html', '.php', '.phtml'];
        foreach($ext as $value) {
            $way = 'App/views/'.$name.$value;
            if(file_exists($way)) {
                return require_once($way);
            }
        }
        trigger_error("supported files are HTML, PHP and PHTML");
    }
}
