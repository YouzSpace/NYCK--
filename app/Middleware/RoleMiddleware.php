<?php

declare(strict_types=1);

namespace ShortUrl\Middleware;

use ShortUrl\Services\AuthService;
use ShortUrl\Support\Response;

class RoleMiddleware
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function handle(string $role): void
    {
        $user = $this->authService->currentUser();

        if ($user === null) {
            Response::redirect('/login.php');
        }

        if (($user['role'] ?? null) !== $role) {
            Response::forbidden('无权访问该页面。');
        }
    }
}
