<?php

declare(strict_types=1);

namespace ShortUrl\Tests\Services;

use PHPUnit\Framework\TestCase;
use ShortUrl\Repositories\JsonRepository;
use ShortUrl\Repositories\LinkRepository;
use ShortUrl\Services\LinkService;

class LinkServiceTest extends TestCase
{
    private string $filePath;
    private LinkService $service;
    private array $operator;

    protected function setUp(): void
    {
        $this->filePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'short-url-link-service.json';
        @unlink($this->filePath);
        $repository = new LinkRepository(new JsonRepository(), $this->filePath, ['items' => []]);
        $this->service = new LinkService($repository);
        $this->operator = ['id' => 'user_1', 'role' => 'admin'];
    }

    protected function tearDown(): void
    {
        @unlink($this->filePath);
    }

    public function testCreateLinkSupportsAutoCode(): void
    {
        $link = $this->service->createLink([
            'target_url' => 'https://example.com',
            'custom_code' => '',
        ], $this->operator);

        self::assertNotEmpty($link['code']);
        self::assertSame('https://example.com', $link['target_url']);
    }

    public function testCreateLinkSupportsCustomCode(): void
    {
        $link = $this->service->createLink([
            'target_url' => 'https://example.com/docs',
            'custom_code' => 'docs123',
        ], $this->operator);

        self::assertSame('docs123', $link['code']);
    }
}
