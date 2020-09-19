<?php

namespace Core;

class Controller {
    
    protected function view($name, $params = null) {
        $extName = '.html';

        if ($params) {
            $arrKeys = array_keys($params);
    
            foreach($arrKeys as $value) {
                $$value = $params[$value];
            }
        }

        if (file_exists('App/views/' . $name . '.html')) {
            $extName = '.html';
        } else if (file_exists('App/views/' . $name . '.phtml')) {
            $extName = '.phtml';
        } else if (file_exists('App/views/' . $name . '.php')) {
            $extName = '.php';
        } else {
            throw new Exception('view not found');
        }

        return require_once('App/views/' . $name . $extName);
    }
}
