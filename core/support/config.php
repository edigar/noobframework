<?php

$config = file_exists('config/config.php') && is_file('config/config.php') ? include_once 'config/AppConfig.php' : [];

/**
 * Get configs (one or all) on config/config.php
 *
 * @param string|null $item AppConfig index wanted (optional)
 *
 * @return mixed Value of config
 */
function config(string $item = null): mixed {
    global $config;

    if($item != null) {
        return isset($config[$item]) ? $config[$item] : null;
    }

    return $config;
}
