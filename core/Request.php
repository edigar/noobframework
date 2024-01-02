<?php

namespace Core;

class Request {

    /** @var array */
    private array $params;

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
     * @return bool Whether the method is specified
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
        return $_SERVER['HTTP_CLIENT_IP'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']);
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
     * Get params from URI
     *
     * @return mixed|null Get params
     */
    public function var(string $inputName = null): mixed {
        if($inputName !== null) return $this->params['var'][$inputName] ?? null;
        else return $this->params['var'] ?? null;
    }

    /**
     * Get params from querystring
     * 
     * @return mixed|null Get params
     */
    public function query(string $inputName = null): mixed {
        if($inputName !== null) return $this->params['query'][$inputName] ?? null;
        else return $this->params['query'] ?? null;
    }

    /**
     * Get params from body
     *
     * @param string|null   $inputName parameter name (optional)
     * @param mixed|null    $default If parameter not found, this will be returned (optional)
     *
     * @return mixed Post params
     */
    public function body(string $inputName = null, mixed $default = null): mixed {
        if($this->isMethod('POST') || $this->isMethod('PATCH') || $this->isMethod('PUT')) {
            if($inputName !== null) return $this->params['body'][$inputName] ?? $default;
            else return $this->params['body'] ?? null;
        }

        return $default;
    }
}
