<?php

declare(strict_types=1);

namespace ShortUrl\Services;

use InvalidArgumentException;
use ShortUrl\Repositories\UserRepository;
use ShortUrl\Support\Validator;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function listUsers(array $filters = []): array
    {
        $items = $this->userRepository->all();

        return array_values(array_filter($items, static function (array $user) use ($filters): bool {
            if (($filters['role'] ?? '') !== '' && ($user['role'] ?? null) !== $filters['role']) {
                return false;
            }

            if (($filters['status'] ?? '') !== '' && ($user['status'] ?? null) !== $filters['status']) {
                return false;
            }

            return true;
        }));
    }

    public function createUser(array $data): array
    {
        return $this->createUserWithOptions($data, true);
    }

    public function registerUser(array $data): array
    {
        return $this->createUserWithOptions([
            'username' => $data['username'] ?? '',
            'password' => $data['password'] ?? '',
            'role' => 'user',
            'status' => (string) ($data['status'] ?? 'active'),
            'source' => 'public_register',
        ], false);
    }

    private function createUserWithOptions(array $data, bool $allowRoleInput): array
    {
        $username = trim((string) ($data['username'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $role = $allowRoleInput ? (string) ($data['role'] ?? 'user') : 'user';
        $status = (string) ($data['status'] ?? 'active');

        if (!Validator::username($username)) {
            throw new InvalidArgumentException('用户名格式不合法。');
        }

        if (!Validator::password($password)) {
            throw new InvalidArgumentException('密码至少需要 6 位。');
        }

        if (!Validator::role($role) || !Validator::status($status)) {
            throw new InvalidArgumentException('角色或状态不合法。');
        }

        if ($this->userRepository->findByUsername($username) !== null) {
            throw new InvalidArgumentException('用户名已存在。');
        }

        $user = [
            'id' => uniqid('user_', true),
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'status' => $status,
            'created_at' => date(DATE_ATOM),
            'updated_at' => date(DATE_ATOM),
        ];

        if (isset($data['source']) && is_string($data['source']) && $data['source'] !== '') {
            $user['source'] = $data['source'];
        }

        return $this->userRepository->create($user);
    }

    public function updateUser(string $id, array $data): array
    {
        $user = $this->userRepository->findById($id);
        if ($user === null) {
            throw new InvalidArgumentException('用户不存在。');
        }

        $payload = [];

        if (isset($data['role'])) {
            $role = (string) $data['role'];
            if (!Validator::role($role)) {
                throw new InvalidArgumentException('角色不合法。');
            }
            $payload['role'] = $role;
        }

        if (isset($data['status'])) {
            $status = (string) $data['status'];
            if (!Validator::status($status)) {
                throw new InvalidArgumentException('状态不合法。');
            }
            $payload['status'] = $status;
        }

        if (isset($data['password']) && $data['password'] !== '') {
            $password = (string) $data['password'];
            if (!Validator::password($password)) {
                throw new InvalidArgumentException('密码至少需要 6 位。');
            }
            $payload['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $payload['updated_at'] = date(DATE_ATOM);

        return $this->userRepository->update($id, $payload) ?? $user;
    }

    public function disableUser(string $id): array
    {
        $user = $this->userRepository->disable($id);
        if ($user === null) {
            throw new InvalidArgumentException('用户不存在。');
        }

        return $user;
    }

    public function findUserById(string $id): ?array
    {
        return $this->userRepository->findById($id);
    }

    public function findUserByUsername(string $username): ?array
    {
        return $this->userRepository->findByUsername($username);
    }
}
