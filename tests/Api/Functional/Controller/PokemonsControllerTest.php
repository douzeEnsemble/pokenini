<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

use App\Api\Controller\PokemonsController;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(PokemonsController::class)]
class PokemonsControllerTest extends AbstractTestControllerApi
{
    public function testGetListFromDex(): void
    {
        $this->apiRequest('GET', 'api/pokemons/list/home/12');

        $this->assertResponseIsOK();

        /** @var string[][] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(12, $content);

        foreach ($content as $pokemon) {
            $this->assertArrayHasKey('pokemon_slug', $pokemon);
            $this->assertArrayHasKey('pokemon_french_name', $pokemon);
            $this->assertArrayHasKey('pokemon_icon', $pokemon);
        }
    }

    public function testGetListFromDexBis(): void
    {
        $this->apiRequest('GET', 'api/pokemons/list/redgreenblueyellow/12');

        $this->assertResponseIsOK();

        /** @var string[][] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(7, $content);

        foreach ($content as $pokemon) {
            $this->assertArrayHasKey('pokemon_slug', $pokemon);
            $this->assertArrayHasKey('pokemon_french_name', $pokemon);
            $this->assertArrayHasKey('pokemon_icon', $pokemon);
        }
    }

    public function testGetAuth(): void
    {
        $this->apiRequest('GET', 'api/pokemons/list/home/12', [], ['PHP_AUTH_USER' => 'web', 'PHP_AUTH_PW' => 'douze']);

        $this->assertResponseIsOK();

        /** @var string[] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(12, $content);
    }

    public function testGetBadAuth(): void
    {
        $this->apiRequest('GET', 'api/pokemons/list/home/12', [], ['PHP_AUTH_USER' => 'web', 'PHP_AUTH_PW' => 'treize']);

        $this->assertEquals(401, $this->getResponse()->getStatusCode());
    }
}
