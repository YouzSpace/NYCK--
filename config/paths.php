<?php

declare(strict_types=1);

$rootPath = realpath(__DIR__ . '/..');

if ($rootPath === false) {
    throw new RuntimeException('Unable to resolve project root path.');
}

$storagePath = $rootPath . DIRECTORY_SEPARATOR . 'storage';
$dataPath = $storagePath . DIRECTORY_SEPARATOR . 'data';

return [
    'root' => $rootPath,
    'app' => $rootPath . DIRECTORY_SEPARATOR . 'app',
    'config' => $rootPath . DIRECTORY_SEPARATOR . 'config',
    'public' => $rootPath . DIRECTORY_SEPARATOR . 'public',
    'assets' => $rootPath . DIRECTORY_SEPARATOR . 'assets',
    'storage' => $storagePath,
    'storage_data' => $dataPath,
    'storage_cache' => $storagePath . DIRECTORY_SEPARATOR . 'cache',
    'storage_backup' => $storagePath . DIRECTORY_SEPARATOR . 'backup',
    'tests' => $rootPath . DIRECTORY_SEPARATOR . 'tests',
    'users_file' => $dataPath . DIRECTORY_SEPARATOR . 'users.json',
    'links_file' => $dataPath . DIRECTORY_SEPARATOR . 'links.json',
    'logs_file' => $dataPath . DIRECTORY_SEPARATOR . 'logs.json',
    'config_file' => $dataPath . DIRECTORY_SEPARATOR . 'config.json',
];
