<?php

namespace Core;

class Request {

    private $params;

    public function __construct(array $params = null) {
        $this->params = $params;
    }

    public function getParams() {
        return $this->params;
    }

    public function method(): string  {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isMethod(string $type): bool {
        return $this->method() === strtoupper($type);
    }

    public function ip(): string {
        return isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    }

    public function path(): string {
        return $_SERVER['REQUEST_URI'];
    }

    public function ssl(): bool{
        return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }

    public function fullPath(): string {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public function get() {
        return $this->params[0];
    }

    public function post(string $inputName = null, $default = null) {
        if($this->isMethod('POST')) {
            if($inputName !== null) return isset($this->params[1][$inputName]) ? $this->params[1][$inputName] : $default;
            else return isset($this->params[1]) ? $this->params[1] : null;
        }

        return $default;
    }
}
