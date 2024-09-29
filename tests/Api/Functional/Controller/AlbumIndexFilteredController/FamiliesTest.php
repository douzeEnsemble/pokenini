<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller\AlbumIndexFilteredController;

use App\Api\Controller\AlbumIndexController;
use App\Tests\Api\Common\Traits\PokemonListTrait;
use App\Tests\Api\Common\Traits\ReportTrait\AssertReportTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(AlbumIndexController::class)]
class FamiliesTest extends AbstractTestAlbumIndexFilteredController
{
    use AssertReportTrait;
    use PokemonListTrait;

    public function testFamilyFilter(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'families' => [
                    'bulbasaur',
                ],
            ],
        );

        $this->assertResponseIsOK();
        $content = $this->getResponseContent();

        /** @var int[][][]|string[][]|string[][][] $data */
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('pokemons', $data);

        /** @var string[][]|string[][][] $pokemons */
        $pokemons = $data['pokemons'];

        $this->assertSameSlugs(
            $pokemons,
            [
                'bulbasaur',
                'ivysaur',
                'venusaur',
                'venusaur-f',
                'venusaur-mega',
                'venusaur-gmax',
            ],
        );

        $this->assertArrayHasKey('report', $data);

        /** @var int[]|int[][][]|string[][][] $report */
        $report = $data['report'];

        $this->assertReport($report, 6, 0, 0, 0, 6);
    }

    public function testFamilyFilterNull(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'families' => [
                    'null',
                ],
            ],
        );

        $this->assertResponseIsOK();
        $content = $this->getResponseContent();

        /** @var int[][][]|string[][]|string[][][] $data */
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('pokemons', $data);

        /** @var string[][]|string[][][] $pokemons */
        $pokemons = $data['pokemons'];

        $this->assertCount(0, $pokemons);
    }

    public function testFamiliesFilter(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'families' => [
                    'bulbasaur',
                    'charmander',
                ],
            ],
        );

        $this->assertResponseIsOK();
        $content = $this->getResponseContent();

        /** @var int[][][]|string[][]|string[][][] $data */
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('pokemons', $data);

        /** @var string[][]|string[][][] $pokemons */
        $pokemons = $data['pokemons'];

        $this->assertSameSlugs(
            $pokemons,
            [
                'bulbasaur',
                'ivysaur',
                'venusaur',
                'venusaur-f',
                'venusaur-mega',
                'venusaur-gmax',
                'charmander',
                'charmeleon',
                'charizard',
            ],
        );

        $this->assertArrayHasKey('report', $data);

        /** @var int[]|int[][][]|string[][][] $report */
        $report = $data['report'];

        $this->assertReport($report, 6, 0, 0, 3, 9);
    }
}
