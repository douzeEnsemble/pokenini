<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

use App\Api\Controller\AdminUpdateController;
use App\Api\Message\UpdateCollectionsAvailabilities;
use App\Api\Message\UpdateGamesAvailabilities;
use App\Api\Message\UpdateGamesCollectionsAndDex;
use App\Api\Message\UpdateGamesShiniesAvailabilities;
use App\Api\Message\UpdateLabels;
use App\Api\Message\UpdatePokemons;
use App\Api\Message\UpdateRegionalDexNumbers;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @internal
 */
#[CoversClass(AdminUpdateController::class)]
class AdminUpdateControllerTest extends WebTestCase
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

    public function testUpdateGamesCollectionsAndDex(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/update/games_collections_and_dex',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(UpdateGamesCollectionsAndDex::class, 1);
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

    public function testUpdateCollectionsAvailabilities(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/update/collections_availabilities',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(UpdateCollectionsAvailabilities::class, 1);
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

    public function testUpdateCollections(): void
    {
        $client = static::createClient();

        $this->transport('async')->queue()->assertEmpty();

        $client->request(
            'POST',
            'api/istration/update/collections_availabilities',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(201);

        $this->transport('async')->queue()->assertContains(UpdateCollectionsAvailabilities::class, 1);
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
