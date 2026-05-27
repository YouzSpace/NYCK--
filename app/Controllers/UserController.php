<?php

declare(strict_types=1);

namespace ShortUrl\Controllers;

use ShortUrl\Services\UserService;
use Throwable;

class UserController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function list(array $filters = []): void
    {
        $users = $this->userService->listUsers($filters);
        require dirname(__DIR__) . '/Views/users/list.php';
    }

    public function create(array $input): array
    {
        try {
            return ['user' => $this->userService->createUser($input), 'errors' => []];
        } catch (Throwable $throwable) {
            return ['user' => null, 'errors' => [$throwable->getMessage()]];
        }
    }

    public function update(string $id, array $input): array
    {
        try {
            return ['user' => $this->userService->updateUser($id, $input), 'errors' => []];
        } catch (Throwable $throwable) {
            return ['user' => null, 'errors' => [$throwable->getMessage()]];
        }
    }

    public function disable(string $id): array
    {
        try {
            return ['user' => $this->userService->disableUser($id), 'errors' => []];
        } catch (Throwable $throwable) {
            return ['user' => null, 'errors' => [$throwable->getMessage()]];
        }
    }
}
