<?php

namespace Core;

class Controller {
    
    private $config = [];

    public function __construct () {
        global $config;
        $this->config = $config;
    }

    protected function getConfig($config = null) {
        if($config != null) {
            return $this->config[$config];
        }

        return $this->config;
    }
    
    protected function view($name, $params = null) {

        if ($params) {
            $arrKeys = array_keys($params);
    
            foreach($arrKeys as $value) {
                $$value = $params[$value];
            }
        }

        $extensionsAllowed = ['.html', '.php', '.phtml'];
        foreach($extensionsAllowed as $extension) {
            $view = 'App/views/' . $name . $extension;
            if(file_exists($view)) {
                return require_once($view);
            }
        }

        throw new \Exception('view not found');
    }

    protected function redirect($url, $statusCode = null) {
        if($statusCode == null) {
            header('Location: ' . $url);
        } else {
            header('Location: ' . $url, true, $statusCode);
        }
        
        die();
    }
}
