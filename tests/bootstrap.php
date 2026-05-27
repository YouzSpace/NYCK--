<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefix = 'ShortUrl\\';
    $baseDirectory = dirname(__DIR__) . '/app/';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDirectory . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
