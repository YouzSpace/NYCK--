<?php

declare(strict_types=1);

namespace ShortUrl\Repositories;

use JsonException;
use RuntimeException;
use ShortUrl\Support\FileLock;

class JsonRepository
{
    public function readJson(string $filePath, array $default = []): array
    {
        $this->ensureDirectory(dirname($filePath));

        if (!is_file($filePath) || filesize($filePath) === 0) {
            return $default;
        }

        $content = file_get_contents($filePath);

        if ($content === false || trim($content) === '') {
            return $default;
        }

        try {
            $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            return $default;
        }

        return is_array($decoded) ? $decoded : $default;
    }

    public function writeJson(string $filePath, array $data): void
    {
        $this->ensureDirectory(dirname($filePath));

        FileLock::exclusive($filePath, static function ($handle) use ($data): void {
            try {
                $encoded = json_encode(
                    $data,
                    JSON_PRETTY_PRINT
                    | JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
                    | JSON_THROW_ON_ERROR
                );
            } catch (JsonException $exception) {
                throw new RuntimeException('Unable to encode JSON data.', 0, $exception);
            }

            if (!is_resource($handle)) {
                throw new RuntimeException('Invalid file handle received for JSON write.');
            }

            rewind($handle);
            if (!ftruncate($handle, 0)) {
                throw new RuntimeException('Unable to truncate JSON file before writing.');
            }

            if (fwrite($handle, $encoded . PHP_EOL) === false) {
                throw new RuntimeException('Unable to write JSON file contents.');
            }

            fflush($handle);
        });
    }

    private function ensureDirectory(string $directory): void
    {
        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Unable to create directory: %s', $directory));
        }
    }
}
