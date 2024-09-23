<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

use App\Api\Controller\AdminCalculateController;
use App\Api\Message\CalculateDexAvailabilities;
use App\Api\Message\CalculateGameBundlesAvailabilities;
use App\Api\Message\CalculateGameBundlesShiniesAvailabilities;
use App\Api\Message\CalculatePokemonAvailabilities;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @internal
 */
#[CoversClass(AdminCalculateController::class)]
class AdminCalculateControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    use InteractsWithMessenger;

    public function testCalculateGameBundlesAvailabilities(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/calculate/game_bundles_availabilities',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(CalculateGameBundlesAvailabilities::class, 1);
    }

    public function testCalculateGameBundlesShiniesAvailabilities(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/calculate/game_bundles_shinies_availabilities',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(CalculateGameBundlesShiniesAvailabilities::class, 1);
    }

    public function testCalculateDexAvailabilities(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/calculate/dex_availabilities',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(CalculateDexAvailabilities::class, 1);
    }

    public function testCalculatePokemonAvailabilities(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/calculate/pokemon_availabilities',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(CalculatePokemonAvailabilities::class, 1);
    }

    public function testUpdateBadAuth(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            'api/istration/calculate/game_bundles_shinies_availabilities',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'treize',
            ],
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
