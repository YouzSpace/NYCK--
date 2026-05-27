<?php

declare(strict_types=1);

namespace ShortUrl\Tests\Views;

use PHPUnit\Framework\TestCase;

class LayoutPermissionTest extends TestCase
{
    public function testUserRoleDoesNotSeeUserManagementNavigation(): void
    {
        $_GET = ['page' => 'dashboard'];
        $currentUser = [
            'username' => 'member',
            'role' => 'user',
        ];
        $title = '仪表盘';
        $content = '<p>content</p>';

        ob_start();
        require dirname(__DIR__, 2) . '/app/Views/partials/layout.php';
        $html = (string) ob_get_clean();

        self::assertStringNotContainsString('/index.php?page=users', $html);
        self::assertStringContainsString('普通用户', $html);
    }

    public function testMissingCurrentUserUsesSafeUserRoleFallback(): void
    {
        $_GET = ['page' => 'dashboard'];
        $title = '仪表盘';
        $content = '<p>content</p>';

        ob_start();
        require dirname(__DIR__, 2) . '/app/Views/partials/layout.php';
        $html = (string) ob_get_clean();

        self::assertStringNotContainsString('/index.php?page=users', $html);
        self::assertStringContainsString('当前用户', $html);
        self::assertStringContainsString('普通用户', $html);
    }

    public function testAdminRoleSeesUserManagementNavigation(): void
    {
        $_GET = ['page' => 'dashboard'];
        $currentUser = [
            'username' => 'admin',
            'role' => 'admin',
        ];
        $title = '仪表盘';
        $content = '<p>content</p>';

        ob_start();
        require dirname(__DIR__, 2) . '/app/Views/partials/layout.php';
        $html = (string) ob_get_clean();

        self::assertStringContainsString('/index.php?page=users', $html);
        self::assertStringContainsString('管理员', $html);
    }
}
