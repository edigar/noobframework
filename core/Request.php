<?php

namespace Core;

class Request {

    /** @var array */
    private $params;

    public function __construct(array $params) {
        $this->params = $params;
    }

    /**
     * Get parameters sent by GET or POST
     * 
     * @return array parameters
     */
    public function getParams(): array {
        return $this->params;
    }

    /**
     * Get request method
     * 
     * @return string method
     */
    public function method(): string  {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Checks whether the request method is the one specified
     * 
     * @param string $type Request method to be checked
     * 
     * @return bool Whether or not the method is specified
     */
    public function isMethod(string $type): bool {
        return $this->method() === strtoupper($type);
    }

    /**
     * Get client IP
     * 
     * @return string ip
     */
    public function ip(): string {
        return isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
    }

    /**
     * Get URI
     * 
     * @return string uri
     */
    public function path(): string {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Request is using SSL?
     * 
     * @return bool
     */
    public function ssl(): bool{
        return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }

    /**
     * Get full path URL
     * 
     * @return string URL
     */
    public function fullPath(): string {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    /**
     * Get params from GET method
     * 
     * @return mixed Get params
     */
    public function get() {
        return isset($this->params['get']) ? $this->params['get'] : null;
    }

    /**
     * Get params (one or all) from POST method
     * 
     * @param string    $inputName  An especific parameter (optional)
     * @param mixed     $default    If parameter not found, this will be returned (optional)
     * 
     * @return mixed Post params
     */
    public function post(string $inputName = null, $default = null) {
        if($this->isMethod('POST')) {
            if($inputName !== null) return isset($this->params['post'][$inputName]) ? $this->params['post'][$inputName] : $default;
            else return isset($this->params['post']) ? $this->params['post'] : null;
        }

        return $default;
    }
}
