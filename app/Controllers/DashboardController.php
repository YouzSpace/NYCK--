<?php

declare(strict_types=1);

namespace ShortUrl\Controllers;

use ShortUrl\Services\StatsService;

class DashboardController
{
    public function __construct(private readonly StatsService $statsService)
    {
    }

    public function index(array $operator): void
    {
        $stats = $this->statsService->getDashboardStats($operator);
        require dirname(__DIR__) . '/Views/dashboard/index.php';
    }
}
