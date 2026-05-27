<?php

declare(strict_types=1);

namespace ShortUrl\Tests\Services;

use PHPUnit\Framework\TestCase;
use ShortUrl\Repositories\JsonRepository;
use ShortUrl\Repositories\LinkRepository;
use ShortUrl\Services\StatsService;

class StatsServiceTest extends TestCase
{
    private string $filePath;
    private StatsService $service;

    protected function setUp(): void
    {
        $this->filePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'short-url-stats-service.json';
        @unlink($this->filePath);
        $repository = new LinkRepository(new JsonRepository(), $this->filePath, ['items' => []]);
        $repository->create([
            'id' => 'link_1',
            'user_id' => 'user_1',
            'code' => 'alpha1',
            'target_url' => 'https://example.com',
            'status' => 'active',
            'pv' => 0,
            'expires_at' => null,
            'created_at' => date(DATE_ATOM),
            'updated_at' => date(DATE_ATOM),
        ]);

        $this->service = new StatsService($repository);
    }

    protected function tearDown(): void
    {
        @unlink($this->filePath);
    }

    public function testIncreasePvAddsOne(): void
    {
        $link = $this->service->increasePv('link_1');
        self::assertSame(1, $link['pv']);
    }
}
