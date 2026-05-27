<?php

declare(strict_types=1);

namespace ShortUrl\Controllers;

class LandingController
{
    public function showHome(): void
    {
        $features = $this->getLandingContent();
        $entryLinks = $this->getAuthEntryLinks();

        require dirname(__DIR__) . '/Views/landing/home.php';
    }

    /**
     * @return array<int, array{title: string, description: string}>
     */
    public function getLandingContent(): array
    {
        return [
            [
                'title' => '短链接生成',
                'description' => '将长链接转换为简洁短码，便于分享和传播。',
            ],
            [
                'title' => '访问统计',
                'description' => '记录每条短链接的累计访问量，快速了解链接使用情况。',
            ],
            [
                'title' => '过期控制',
                'description' => '为短链接设置有效期，到期后自动停止访问。',
            ],
            [
                'title' => '多账号协作',
                'description' => '支持管理员和普通用户分工管理短链接。',
            ],
        ];
    }

    /**
     * @return array{login: string, register: string}
     */
    public function getAuthEntryLinks(): array
    {
        return [
            'login' => '/login.php',
            'register' => '/register.php',
        ];
    }
}
