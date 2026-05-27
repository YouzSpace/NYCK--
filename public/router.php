<?php

declare(strict_types=1);

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

// 让真实存在的文件（PHP/静态资源）正常处理
$publicDir = __DIR__;
$filePath = $publicDir . $requestUri;

if ($requestUri !== '/' && is_file($filePath)) {
    return false;
}

// 根路径 -> 官网主页
if ($requestUri === '/') {
    require $publicDir . '/home.php';
    return true;
}

// 排除已知路由前缀
$knownPrefixes = ['/assets/', '/index.php', '/login.php', '/register.php', '/home.php', '/logout.php', '/go.php'];
foreach ($knownPrefixes as $prefix) {
    if (str_starts_with($requestUri, $prefix)) {
        return false;
    }
}

// 提取短码
$code = trim($requestUri, '/');
if ($code === '') {
    require $publicDir . '/home.php';
    return true;
}

// 跳转逻辑
require dirname(__DIR__) . '/tests/bootstrap.php';

$paths = require dirname(__DIR__) . '/config/paths.php';
$appConfig = require dirname(__DIR__) . '/config/app.php';

use ShortUrl\Repositories\JsonRepository;
use ShortUrl\Repositories\LinkRepository;
use ShortUrl\Services\LinkService;
use ShortUrl\Services\StatsService;
use ShortUrl\Support\Response;

$linkRepository = new LinkRepository(
    new JsonRepository(),
    $paths['links_file'],
    $appConfig['defaults']['links'] ?? ['items' => []]
);
$linkService = new LinkService($linkRepository);
$statsService = new StatsService($linkRepository);
$link = $linkService->findByShortCode($code);

if ($link === null) {
    Response::notFound('短链不存在。');
}

if (($link['status'] ?? '') !== 'active') {
    Response::forbidden('短链已被停用。');
}

if ($linkService->isLinkExpired($link)) {
    Response::gone('短链已超过有效期。');
}

$statsService->increasePv((string) $link['id']);
Response::redirect((string) $link['target_url']);
