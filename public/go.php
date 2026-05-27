<?php

declare(strict_types=1);

require dirname(__DIR__) . '/public_assets.php';

$container = require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/tests/bootstrap.php';

use ShortUrl\Repositories\JsonRepository;
use ShortUrl\Repositories\LinkRepository;
use ShortUrl\Services\LinkService;
use ShortUrl\Services\StatsService;
use ShortUrl\Support\Response;

$code = trim((string) ($_GET['code'] ?? ''));
if ($code === '') {
    Response::badRequest('缺少短码参数。');
}

$linkRepository = new LinkRepository(
    new JsonRepository(),
    $container['paths']['links_file'],
    $container['config']['defaults']['links']
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
