<?php

namespace Core;

class Controller {

    protected function getConfig(string $item = null): array {
        global $config;

        if($item != null) {
            return $config[$item];
        }

        return $config;
    }
    
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
