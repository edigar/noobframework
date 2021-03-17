<?php

namespace Core;

class Controller {
    
    /**
     * Load view
     * 
     * @param string        $name   View name
     * @param array|null    $params Params for view (optional)
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
     * @param int|null  $statusCode Redirect status code (optional)
     * 
     * @return void
     */
    protected function redirect(string $url, int $statusCode = null): void {
        $urlBase = config('urlBase');
        $urlBase = $urlBase == null ? "" : $urlBase . "/";

        if($statusCode == null) {
            header('Location: ' . $urlBase . $url);
        } else {
            header('Location: ' . $urlBase . $url, true, $statusCode);
        }
        
        die();
    }
}
