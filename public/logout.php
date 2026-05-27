<?php

declare(strict_types=1);

require dirname(__DIR__) . '/public_assets.php';

$container = require dirname(__DIR__) . '/bootstrap.php';

use ShortUrl\Controllers\AuthController;
use ShortUrl\Repositories\JsonRepository;
use ShortUrl\Repositories\UserRepository;
use ShortUrl\Services\AuthService;

require dirname(__DIR__) . '/tests/bootstrap.php';

$userRepository = new UserRepository(
    new JsonRepository(),
    $container['paths']['users_file'],
    $container['config']['defaults']['users']
);
$authService = new AuthService($userRepository);
$controller = new AuthController($authService);
$controller->logout();
