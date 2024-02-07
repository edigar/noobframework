<?php

/**
 * Get environment variable value
 *
 * @param string|null $item config item wanted (optional)
 * @param mixed $default default value if item isn't set (optional)
 *
 * @return mixed Value of config
 */
function config(string $item = null, mixed $default = null): mixed {
    return $_ENV[$item] ?? $default;
}
