<?php

declare(strict_types=1);

namespace ShortUrl\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use ShortUrl\Repositories\JsonRepository;

class JsonRepositoryTest extends TestCase
{
    private string $tempDirectory;
    private JsonRepository $repository;

    protected function setUp(): void
    {
        $this->tempDirectory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'short-url-json-repository-tests';
        $this->removeDirectory($this->tempDirectory);
        $this->repository = new JsonRepository();
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tempDirectory);
    }

    public function testReadJsonReturnsDefaultWhenFileDoesNotExist(): void
    {
        $filePath = $this->tempDirectory . DIRECTORY_SEPARATOR . 'missing.json';
        $default = ['items' => []];

        self::assertSame($default, $this->repository->readJson($filePath, $default));
    }

    public function testWriteJsonCreatesDirectoryAndPersistsData(): void
    {
        $filePath = $this->tempDirectory . DIRECTORY_SEPARATOR . 'nested' . DIRECTORY_SEPARATOR . 'data.json';
        $payload = ['items' => [['id' => 1, 'name' => 'demo']]];

        $this->repository->writeJson($filePath, $payload);

        self::assertFileExists($filePath);
        self::assertSame($payload, $this->repository->readJson($filePath, []));
    }

    public function testReadJsonFallsBackToDefaultWhenJsonIsInvalid(): void
    {
        $filePath = $this->tempDirectory . DIRECTORY_SEPARATOR . 'broken.json';
        if (!is_dir($this->tempDirectory)) {
            mkdir($this->tempDirectory, 0777, true);
        }
        file_put_contents($filePath, '{invalid json');

        self::assertSame(['safe' => true], $this->repository->readJson($filePath, ['safe' => true]));
    }

    private function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $items = scandir($directory);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }

        rmdir($directory);
    }
}
