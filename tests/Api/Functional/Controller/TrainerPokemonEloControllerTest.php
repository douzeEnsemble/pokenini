<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

use App\Api\Controller\TrainerPokemonEloController;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(TrainerPokemonEloController::class)]
class TrainerPokemonEloControllerTest extends AbstractTestControllerApi
{
    public function testGetTop(): void
    {
        $this->apiRequest(
            'GET',
            'api/election/top',
            [
                'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'dex_slug' => 'home',
                'election_slug' => 'favorite',
                'count' => '5',
            ]
        );

        $this->assertResponseIsOK();

        /** @var string[][] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(5, $content);

        foreach ($content as $pokemon) {
            $this->assertArrayHasKey('elo', $pokemon);
            $this->assertArrayHasKey('pokemon_slug', $pokemon);
            $this->assertArrayHasKey('pokemon_french_name', $pokemon);
            $this->assertArrayHasKey('pokemon_icon', $pokemon);
        }
    }

    public function testGetTopBis(): void
    {
        $this->apiRequest(
            'GET',
            'api/election/top',
            [
                'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'dex_slug' => 'demo',
                'election_slug' => '',
                'count' => '5',
            ]
        );

        $this->assertResponseIsOK();

        /** @var string[][] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(5, $content);

        foreach ($content as $pokemon) {
            $this->assertArrayHasKey('elo', $pokemon);
            $this->assertArrayHasKey('pokemon_slug', $pokemon);
            $this->assertArrayHasKey('pokemon_french_name', $pokemon);
            $this->assertArrayHasKey('pokemon_icon', $pokemon);
        }
    }

    public function testGetAuth(): void
    {
        $this->apiRequest(
            'GET',
            'api/election/top',
            [
                'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'dex_slug' => 'home',
                'election_slug' => 'favorite',
                'count' => '5',
            ],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ]
        );

        $this->assertResponseIsOK();

        /** @var string[] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(5, $content);
    }

    public function testGetBadAuth(): void
    {
        $this->apiRequest(
            'GET',
            'api/election/top',
            [
                'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'dex_slug' => 'home',
                'election_slug' => 'favorite',
                'count' => '5',
            ],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'treize',
            ]
        );

        $this->assertEquals(401, $this->getResponse()->getStatusCode());
    }
}
