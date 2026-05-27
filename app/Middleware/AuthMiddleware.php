<?php

declare(strict_types=1);

namespace ShortUrl\Middleware;

use ShortUrl\Services\AuthService;
use ShortUrl\Support\Response;

class AuthMiddleware
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function handle(): void
    {
        if (!$this->authService->check()) {
            Response::redirect('/login.php');
        }
    }
}
