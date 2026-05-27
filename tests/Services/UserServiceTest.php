<?php

declare(strict_types=1);

namespace ShortUrl\Tests\Services;

use PHPUnit\Framework\TestCase;
use ShortUrl\Repositories\JsonRepository;
use ShortUrl\Repositories\UserRepository;
use ShortUrl\Services\UserService;

class UserServiceTest extends TestCase
{
    private string $filePath;
    private UserService $service;

    protected function setUp(): void
    {
        $this->filePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'short-url-user-service.json';
        @unlink($this->filePath);
        $repository = new UserRepository(new JsonRepository(), $this->filePath, ['items' => []]);
        $this->service = new UserService($repository);
    }

    protected function tearDown(): void
    {
        @unlink($this->filePath);
    }

    public function testCreateUserHashesPassword(): void
    {
        $user = $this->service->createUser([
            'username' => 'editor_01',
            'password' => 'secret123',
            'role' => 'user',
            'status' => 'active',
        ]);

        self::assertNotSame('secret123', $user['password']);
        self::assertTrue(password_verify('secret123', $user['password']));
    }

    public function testRegisterUserAlwaysCreatesNormalUser(): void
    {
        $user = $this->service->registerUser([
            'username' => 'member_01',
            'password' => 'secret123',
            'role' => 'admin',
            'status' => 'active',
        ]);

        self::assertSame('user', $user['role']);
        self::assertSame('active', $user['status']);
        self::assertSame('public_register', $user['source']);
    }

    public function testRegisterUserRejectsDuplicateUsername(): void
    {
        $this->service->registerUser([
            'username' => 'member_02',
            'password' => 'secret123',
        ]);

        $this->expectExceptionMessage('用户名已存在。');

        $this->service->registerUser([
            'username' => 'member_02',
            'password' => 'secret123',
        ]);
    }
}
