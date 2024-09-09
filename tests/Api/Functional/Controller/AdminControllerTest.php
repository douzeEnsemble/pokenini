<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

use App\Api\Controller\AdminController;
use App\Api\Message\CalculateDexAvailabilities;
use App\Api\Message\CalculateGameBundlesAvailabilities;
use App\Api\Message\CalculateGameBundlesShiniesAvailabilities;
use App\Api\Message\CalculatePokemonAvailabilities;
use App\Api\Message\UpdateGamesAndDex;
use App\Api\Message\UpdateGamesAvailabilities;
use App\Api\Message\UpdateGamesShiniesAvailabilities;
use App\Api\Message\UpdateLabels;
use App\Api\Message\UpdatePokemons;
use App\Api\Message\UpdateRegionalDexNumbers;
use App\Api\Service\CalculatorService\AbstractCalculatorService;
use App\Api\Service\CalculatorService\DexAvailabilitiesCalculatorService;
use App\Api\Service\CalculatorService\GameBundlesAvailabilitiesCalculatorService;
use App\Api\Service\CalculatorService\GameBundlesShiniesAvailabilitiesCalculatorService;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @internal
 */
#[CoversClass(AdminController::class)]
#[CoversClass(AbstractCalculatorService::class)]
#[CoversClass(DexAvailabilitiesCalculatorService::class)]
#[CoversClass(GameBundlesAvailabilitiesCalculatorService::class)]
#[CoversClass(GameBundlesShiniesAvailabilitiesCalculatorService::class)]
class AdminControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    use InteractsWithMessenger;

    public function testUpdateLabels(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/update/labels',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(UpdateLabels::class, 1);
    }

    public function testUpdateGamesAndDex(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/update/games_and_dex',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(UpdateGamesAndDex::class, 1);
    }

    public function testUpdatePokemons(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/update/pokemons',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(UpdatePokemons::class, 1);
    }

    public function testUpdateGamesAvailabilities(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/update/games_availabilities',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(UpdateGamesAvailabilities::class, 1);
    }

    public function testUpdateGamesShiniesAvailabilities(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/update/games_shinies_availabilities',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(UpdateGamesShiniesAvailabilities::class, 1);
    }

    public function testUpdateRegionalDexNumbers(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/update/regional_dex_numbers',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(UpdateRegionalDexNumbers::class, 1);
    }

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
            'api/istration/update/labels',
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
