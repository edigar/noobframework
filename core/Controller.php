<?php

namespace Core;

class Controller {

    /**
     * Get configs (one or all) on cofig/cofig.php
     * 
     * @param string $item Config index wanted (optional)
     * 
     * @return mixed Value of config
     */
    protected function getConfig(string $item = null): mixed {
        global $config;

        if($item != null) {
            return $config[$item];
        }

        return $config;
    }
    
    /**
     * Load view
     * 
     * @param string    $name   View name
     * @param array     $params Params for view (optional)
     * 
     * @return string view
     */
    protected function view(string $name, array $params = null): string {
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

     /**
     * Redirects to provided url
     * 
     * @param string    $url
     * @param array     $statusCode Redirect status code (optional)
     * 
     * @return void
     */
    protected function redirect(string $url, int $statusCode = null): void {
        $urlBase = $this->getConfig('urlBase') == null ? "" : $this->getConfig('urlBase') . "/";

        if($statusCode == null) {
            header('Location: ' . $urlBase . $url);
        } else {
            header('Location: ' . $urlBase . $url, true, $statusCode);
        }
        
        die();
    }
}
