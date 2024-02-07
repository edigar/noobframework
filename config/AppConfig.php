<?php

namespace config;

use Dotenv\Dotenv;

class AppConfig
{
    /** @var Dotenv */
    private Dotenv $dotenv;

    public function __construct()
    {
        $this->dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '..\\..\\');
    }

    /**
     * Load configs
     *
     * @return void
     */
    public function load(): void
    {
        $this->dotenv->load();
    }
}