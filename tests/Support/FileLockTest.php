<?php

declare(strict_types=1);

namespace ShortUrl\Tests\Support;

use PHPUnit\Framework\TestCase;
use ShortUrl\Support\FileLock;

class FileLockTest extends TestCase
{
    public function testExclusiveCreatesLockFileAndExecutesCallback(): void
    {
        $lockFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'short-url-file-lock-test.lock';

        if (file_exists($lockFile)) {
            unlink($lockFile);
        }

        $result = FileLock::exclusive($lockFile, static function ($handle): string {
            fwrite($handle, 'locked');

            return 'done';
        });

        self::assertSame('done', $result);
        self::assertFileExists($lockFile);
        self::assertStringContainsString('locked', (string) file_get_contents($lockFile));

        unlink($lockFile);
    }
}
