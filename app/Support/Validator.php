<?php

declare(strict_types=1);

namespace ShortUrl\Support;

class Validator
{
    public static function required(?string $value): bool
    {
        return trim((string) $value) !== '';
    }

    public static function username(string $username): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9_\-]{3,32}$/', $username);
    }

    public static function password(string $password): bool
    {
        return mb_strlen($password) >= 6;
    }

    public static function role(string $role): bool
    {
        return in_array($role, ['admin', 'user'], true);
    }

    public static function status(string $status): bool
    {
        return in_array($status, ['active', 'disabled'], true);
    }

    public static function url(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public static function shortCode(string $code): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9_-]{1,32}$/', $code);
    }
}
