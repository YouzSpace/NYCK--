<?php

declare(strict_types=1);

$container = require dirname(__DIR__) . '/bootstrap.php';

use ShortUrl\Controllers\AuthController;
use ShortUrl\Repositories\JsonRepository;
use ShortUrl\Repositories\UserRepository;
use ShortUrl\Services\AuthService;
use ShortUrl\Services\UserService;

require dirname(__DIR__) . '/tests/bootstrap.php';
require dirname(__DIR__) . '/public_assets.php';

$userRepository = new UserRepository(
    new JsonRepository(),
    $container['paths']['users_file'],
    $container['config']['defaults']['users']
);
$authService = new AuthService($userRepository);
$userService = new UserService($userRepository);
$controller = new AuthController($authService, $userService);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->register($_POST);
    return;
}

$controller->showRegister();
