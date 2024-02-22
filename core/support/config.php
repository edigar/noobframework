<?php

/**
 * Get environment variable value
 *
 * @param string $item config item wanted
 * @param mixed $default default value if item isn't set (optional)
 *
 * @return mixed Value of config
 */
function config(string $item, mixed $default = null): mixed
{
    return $_ENV[$item] ?? $default;
}
