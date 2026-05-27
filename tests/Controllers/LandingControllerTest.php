<?php

declare(strict_types=1);

namespace ShortUrl\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use ShortUrl\Controllers\LandingController;

class LandingControllerTest extends TestCase
{
    public function testLandingContentContainsPublicFeatures(): void
    {
        $controller = new LandingController();
        $features = $controller->getLandingContent();

        self::assertCount(4, $features);
        self::assertSame('短链接生成', $features[0]['title']);
        self::assertSame('访问统计', $features[1]['title']);
        self::assertSame('过期控制', $features[2]['title']);
        self::assertSame('多账号协作', $features[3]['title']);
    }

    public function testAuthEntryLinksPointToLoginAndRegister(): void
    {
        $controller = new LandingController();
        $links = $controller->getAuthEntryLinks();

        self::assertSame('/login.php', $links['login']);
        self::assertSame('/register.php', $links['register']);
    }
}
