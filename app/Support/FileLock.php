<?php

declare(strict_types=1);

namespace ShortUrl\Support;

use RuntimeException;

class FileLock
{
    /**
     * @template T
     * @param callable(resource): T $callback
     * @return T
     */
    public static function exclusive(string $filePath, callable $callback)
    {
        $directory = dirname($filePath);

        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Unable to create lock directory: %s', $directory));
        }

        $handle = fopen($filePath, 'c+');

        if ($handle === false) {
            throw new RuntimeException(sprintf('Unable to open lock file: %s', $filePath));
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new RuntimeException(sprintf('Unable to acquire lock for file: %s', $filePath));
            }

            return $callback($handle);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
