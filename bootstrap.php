<?php

declare(strict_types=1);

$paths = require __DIR__ . '/config/paths.php';
$appConfig = require __DIR__ . '/config/app.php';

require __DIR__ . '/tests/bootstrap.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name((string) ($appConfig['session_name'] ?? 'short_url_admin'));
    session_start();
}

if (!empty($appConfig['timezone'])) {
    date_default_timezone_set((string) $appConfig['timezone']);
}

foreach (['storage', 'storage_data', 'storage_cache', 'storage_backup'] as $directoryKey) {
    $directory = $paths[$directoryKey] ?? null;

    if (!is_string($directory) || $directory === '') {
        throw new RuntimeException(sprintf('Storage path "%s" is not configured.', $directoryKey));
    }

    if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
        throw new RuntimeException(sprintf('Unable to create directory: %s', $directory));
    }
}

$jsonRepository = new ShortUrl\Repositories\JsonRepository();
$userRepository = new ShortUrl\Repositories\UserRepository(
    $jsonRepository,
    $paths['users_file'],
    $appConfig['defaults']['users'] ?? ['items' => []]
);
$linkRepository = new ShortUrl\Repositories\LinkRepository(
    $jsonRepository,
    $paths['links_file'],
    $appConfig['defaults']['links'] ?? ['items' => []]
);
$configRepository = new ShortUrl\Repositories\ConfigRepository(
    $jsonRepository,
    $paths['config_file'],
    $appConfig['defaults']['config'] ?? []
);

$bootstrapService = new ShortUrl\Services\BootstrapService(
    $userRepository,
    $linkRepository,
    $configRepository,
    $appConfig['bootstrap'] ?? []
);
$bootstrapService->initialize();

return [
    'paths' => $paths,
    'config' => $appConfig,
];
