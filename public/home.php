<?php

declare(strict_types=1);

require dirname(__DIR__) . '/tests/bootstrap.php';
require dirname(__DIR__) . '/public_assets.php';

use ShortUrl\Controllers\LandingController;

$controller = new LandingController();
$controller->showHome();
