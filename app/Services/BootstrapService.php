<?php

declare(strict_types=1);

namespace ShortUrl\Services;

use ShortUrl\Repositories\ConfigRepository;
use ShortUrl\Repositories\LinkRepository;
use ShortUrl\Repositories\UserRepository;

class BootstrapService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly LinkRepository $linkRepository,
        private readonly ConfigRepository $configRepository,
        private readonly array $defaults = []
    ) {
    }

    public function initialize(): void
    {
        $admin = $this->defaults['admin'] ?? [];
        $demoLinks = $this->defaults['demo_links'] ?? [];
        $siteConfig = $this->defaults['site_config'] ?? [];

        $adminUser = $this->ensureAdminUser(is_array($admin) ? $admin : []);
        $this->ensureDemoLinks($adminUser, is_array($demoLinks) ? $demoLinks : []);
        $this->ensureSiteConfig(is_array($siteConfig) ? $siteConfig : []);
    }

    private function ensureAdminUser(array $admin): array
    {
        $username = (string) ($admin['username'] ?? 'admin');
        $existing = $this->userRepository->findByUsername($username);

        if ($existing !== null) {
            return $existing;
        }

        return $this->userRepository->create([
            'id' => (string) ($admin['id'] ?? uniqid('user_', true)),
            'username' => $username,
            'password' => password_hash((string) ($admin['password'] ?? 'admin123456'), PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => date(DATE_ATOM),
            'updated_at' => date(DATE_ATOM),
        ]);
    }

    private function ensureDemoLinks(array $adminUser, array $demoLinks): void
    {
        foreach ($demoLinks as $demoLink) {
            if (!is_array($demoLink)) {
                continue;
            }

            $code = trim((string) ($demoLink['code'] ?? ''));
            $targetUrl = trim((string) ($demoLink['target_url'] ?? ''));

            if ($code === '' || $targetUrl === '') {
                continue;
            }

            if ($this->linkRepository->findByShortCode($code) !== null) {
                continue;
            }

            $this->linkRepository->create([
                'id' => (string) ($demoLink['id'] ?? uniqid('link_', true)),
                'user_id' => (string) ($adminUser['id'] ?? ''),
                'code' => $code,
                'target_url' => $targetUrl,
                'status' => (string) ($demoLink['status'] ?? 'active'),
                'pv' => (int) ($demoLink['pv'] ?? 0),
                'expires_at' => $demoLink['expires_at'] ?? null,
                'created_at' => date(DATE_ATOM),
                'updated_at' => date(DATE_ATOM),
            ]);
        }
    }

    private function ensureSiteConfig(array $siteConfig): void
    {
        $current = $this->configRepository->all();
        $merged = array_merge($siteConfig, $current);

        if (!isset($merged['site_name'])) {
            $merged['site_name'] = '短链接生成系统';
        }

        if (!isset($merged['default_short_code_length'])) {
            $merged['default_short_code_length'] = 6;
        }

        $this->configRepository->save($merged);
    }
}
