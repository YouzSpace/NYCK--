<?php

declare(strict_types=1);

namespace ShortUrl\Controllers;

use ShortUrl\Services\AuthService;
use ShortUrl\Services\UserService;
use ShortUrl\Support\Response;
use ShortUrl\Support\Validator;
use Throwable;

class AuthController
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly ?UserService $userService = null
    ) {
    }

    public function showLogin(array $flash = []): void
    {
        $errors = $flash['errors'] ?? [];
        $message = $flash['message'] ?? '';
        require dirname(__DIR__) . '/Views/auth/login.php';
    }

    public function showRegister(array $flash = []): void
    {
        $errors = $flash['errors'] ?? [];
        $old = $flash['old'] ?? [];
        require dirname(__DIR__) . '/Views/auth/register.php';
    }

    public function login(array $input): void
    {
        $username = trim((string) ($input['username'] ?? ''));
        $password = (string) ($input['password'] ?? '');

        $errors = [];
        if (!Validator::required($username)) {
            $errors[] = '请输入用户名。';
        }

        if (!Validator::required($password)) {
            $errors[] = '请输入密码。';
        }

        if ($errors !== []) {
            $this->showLogin(['errors' => $errors]);
            return;
        }

        if (!$this->authService->login($username, $password)) {
            $this->showLogin(['errors' => ['用户名或密码错误，或账号已停用。']]);
            return;
        }

        Response::redirect('/index.php');
    }

    public function register(array $input): void
    {
        if ($this->userService === null) {
            $this->showRegister(['errors' => ['注册服务暂不可用。'], 'old' => $input]);
            return;
        }

        $username = trim((string) ($input['username'] ?? ''));
        $password = (string) ($input['password'] ?? '');
        $passwordConfirm = (string) ($input['password_confirm'] ?? '');

        $errors = [];
        if (!Validator::username($username)) {
            $errors[] = '用户名需为 3-32 位字母、数字或下划线。';
        }

        if (!Validator::password($password)) {
            $errors[] = '密码至少需要 6 位。';
        }

        if ($password !== $passwordConfirm) {
            $errors[] = '两次输入的密码不一致。';
        }

        if ($errors !== []) {
            $this->showRegister(['errors' => $errors, 'old' => ['username' => $username]]);
            return;
        }

        try {
            $this->userService->registerUser([
                'username' => $username,
                'password' => $password,
            ]);
        } catch (Throwable $throwable) {
            $this->showRegister([
                'errors' => [$throwable->getMessage()],
                'old' => ['username' => $username],
            ]);
            return;
        }

        Response::redirect('/login.php?message=' . rawurlencode('账号注册成功，请登录。'));
    }

    public function logout(): void
    {
        $this->authService->logout();
        Response::redirect('/login.php');
    }
}
