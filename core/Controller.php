<?php

namespace Core;

use Exception;

class Controller {

    /**
     * Load view
     *
     * @param string $name view name
     * @param ?array $params params for view (optional)
     *
     * @throws Exception view not found
     */
    protected function view(string $name, ?array $params = null): string
    {
        if ($params) {
            $arrKeys = array_keys($params);

            foreach($arrKeys as $value) {
                $$value = $params[$value];
            }
        }

        $extensionsAllowed = ['.html', '.php', '.phtml'];
        foreach($extensionsAllowed as $extension) {
            $view = 'app/views/' . $name . $extension;
            if(file_exists($view)) {
                return require_once($view);
            }
        }

        throw new Exception('view not found');
    }

    /**
     * Redirects to provided url
     *
     * @param string $url URL to redirect
     * @param ?int $statusCode redirect status code (optional)
     */
    protected function redirect(string $url, ?int $statusCode = null): void
    {
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
