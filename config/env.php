<?php
/**
 * Minimal .env loader (no Composer needed).
 *
 * Reads KEY=VALUE lines from the project-root .env file. Real environment
 * variables win over the file, so hosting panels can override it.
 */

function env(string $key, $default = null)
{
    static $loaded = false;
    static $values = [];

    if (!$loaded) {
        $loaded = true;
        $file = __DIR__ . '/../.env';
        if (is_readable($file)) {
            foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                    continue;
                }
                [$k, $v] = explode('=', $line, 2);
                $values[trim($k)] = trim(trim($v), "\"'");
            }
        }
    }

    $real = getenv($key);
    if ($real !== false) {
        return $real;
    }

    return $values[$key] ?? $default;
}
