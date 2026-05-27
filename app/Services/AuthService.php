<?php

declare(strict_types=1);

namespace ShortUrl\Services;

use ShortUrl\Repositories\UserRepository;

class AuthService
{
    private const SESSION_KEY = 'auth_user_id';

    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function login(string $username, string $password): bool
    {
        $user = $this->userRepository->findByUsername($username);

        if ($user === null || ($user['status'] ?? '') !== 'active') {
            return false;
        }

        if (!password_verify($password, (string) ($user['password'] ?? ''))) {
            return false;
        }

        $_SESSION[self::SESSION_KEY] = $user['id'];

        return true;
    }

    public function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
    }

    public function currentUser(): ?array
    {
        $id = $_SESSION[self::SESSION_KEY] ?? null;

        if (!is_string($id) || $id === '') {
            return null;
        }

        return $this->userRepository->findById($id);
    }

    public function check(): bool
    {
        return $this->currentUser() !== null;
    }
}
