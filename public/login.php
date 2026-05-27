<?php

declare(strict_types=1);

$container = require dirname(__DIR__) . '/bootstrap.php';

use ShortUrl\Controllers\AuthController;
use ShortUrl\Repositories\JsonRepository;
use ShortUrl\Repositories\UserRepository;
use ShortUrl\Services\AuthService;

require dirname(__DIR__) . '/tests/bootstrap.php';
require dirname(__DIR__) . '/public_assets.php';

$userRepository = new UserRepository(
    new JsonRepository(),
    $container['paths']['users_file'],
    $container['config']['defaults']['users']
);
$authService = new AuthService($userRepository);
$controller = new AuthController($authService);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->login($_POST);
    return;
}

$controller->showLogin(['message' => (string) ($_GET['message'] ?? '')]);
