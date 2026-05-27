<?php

declare(strict_types=1);

namespace ShortUrl\Tests\Services;

use PHPUnit\Framework\TestCase;
use ShortUrl\Repositories\JsonRepository;
use ShortUrl\Repositories\UserRepository;
use ShortUrl\Services\AuthService;

class AuthServiceTest extends TestCase
{
    private string $filePath;
    private AuthService $authService;

    protected function setUp(): void
    {
        $this->filePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'short-url-auth-users.json';
        @unlink($this->filePath);
        $_SESSION = [];

        $repository = new UserRepository(new JsonRepository(), $this->filePath, ['items' => []]);
        $repository->create([
            'id' => 'user_1',
            'username' => 'admin',
            'password' => password_hash('secret123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => date(DATE_ATOM),
            'updated_at' => date(DATE_ATOM),
        ]);

        $this->authService = new AuthService($repository);
    }

    protected function tearDown(): void
    {
        @unlink($this->filePath);
    }

    public function testLoginSuccessStoresSession(): void
    {
        self::assertTrue($this->authService->login('admin', 'secret123'));
        self::assertTrue($this->authService->check());
    }

    public function testLoginFailureReturnsFalse(): void
    {
        self::assertFalse($this->authService->login('admin', 'wrong-password'));
        self::assertFalse($this->authService->check());
    }
}
