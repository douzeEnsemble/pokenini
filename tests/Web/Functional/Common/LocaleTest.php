<?php

declare(strict_types=1);

namespace App\Tests\Web\Functional\Common;

use App\Web\Controller\HomeController;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(HomeController::class)]
class LocaleTest extends WebTestCase
{
    public function testLocaleOk(): void
    {
        $client = static::createClient();

        $client->request('GET', '/fr');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testLocaleNonOk(): void
    {
        $client = static::createClient();

        $client->request('GET', '/it');

        $this->assertResponseStatusCodeSame(404);
    }
}
