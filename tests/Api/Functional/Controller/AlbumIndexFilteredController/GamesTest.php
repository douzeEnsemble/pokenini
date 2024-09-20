<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller\AlbumIndexFilteredController;

use App\Api\Controller\AlbumIndexController;
use App\Tests\Api\Common\Traits\ReportTrait\AssertReportTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(AlbumIndexController::class)]
class GamesTest extends AbstractTestAlbumIndexFilteredController
{
    use AssertReportTrait;

    public function testOriginalGameBundle(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'original_game_bundles' => [
                    'redgreenblueyellow',
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

        $this->assertCount(12, $pokemons);
        $this->assertEquals('bulbasaur', $pokemons[0]['pokemon_slug']);
        $this->assertEquals('ivysaur', $pokemons[1]['pokemon_slug']);
        $this->assertEquals('venusaur', $pokemons[2]['pokemon_slug']);
        $this->assertEquals('charmander', $pokemons[3]['pokemon_slug']);
        $this->assertEquals('charmeleon', $pokemons[4]['pokemon_slug']);
        $this->assertEquals('charizard', $pokemons[5]['pokemon_slug']);
        $this->assertEquals('caterpie', $pokemons[6]['pokemon_slug']);
        $this->assertEquals('metapod', $pokemons[7]['pokemon_slug']);
        $this->assertEquals('butterfree', $pokemons[8]['pokemon_slug']);
        $this->assertEquals('rattata', $pokemons[9]['pokemon_slug']);
        $this->assertEquals('raticate', $pokemons[10]['pokemon_slug']);
        $this->assertEquals('douze', $pokemons[11]['pokemon_slug']);

        $this->assertArrayHasKey('report', $data);

        /** @var int[]|int[][][]|string[][][] $report */
        $report = $data['report'];

        $this->assertReport($report, 4, 1, 1, 6, 12);
    }

    public function testOriginalGameBundleNull(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'original_game_bundles' => [
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

    public function testGameBundleAvailabilities(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'game_bundle_availabilities' => [
                    'ultrasunultramoon',
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

        $this->assertCount(2, $pokemons);
        $this->assertEquals('rattata-alola', $pokemons[0]['pokemon_slug']);
        $this->assertEquals('raticate-alola', $pokemons[1]['pokemon_slug']);

        $this->assertArrayHasKey('report', $data);

        /** @var int[]|int[][][]|string[][][] $report */
        $report = $data['report'];

        $this->assertReport($report, 0, 0, 2, 0, 2);
    }

    public function testGameBundleAvailabilitiesNull(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'game_bundle_availabilities' => [
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

    public function testGameBundleAvailabilitiesNegative(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'game_bundle_availabilities' => [
                    '!ultrasunultramoon',
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

        $this->assertCount(20, $pokemons);

        $this->assertEquals('bulbasaur', $pokemons[0]['pokemon_slug']);
        $this->assertEquals('ivysaur', $pokemons[1]['pokemon_slug']);
        $this->assertEquals('venusaur', $pokemons[2]['pokemon_slug']);
        $this->assertEquals('venusaur-f', $pokemons[3]['pokemon_slug']);
        $this->assertEquals('venusaur-mega', $pokemons[4]['pokemon_slug']);
        $this->assertEquals('venusaur-gmax', $pokemons[5]['pokemon_slug']);
        $this->assertEquals('charmander', $pokemons[6]['pokemon_slug']);
        $this->assertEquals('charmeleon', $pokemons[7]['pokemon_slug']);
        $this->assertEquals('charizard', $pokemons[8]['pokemon_slug']);
        $this->assertEquals('caterpie', $pokemons[9]['pokemon_slug']);
        $this->assertEquals('metapod', $pokemons[10]['pokemon_slug']);
        $this->assertEquals('butterfree', $pokemons[11]['pokemon_slug']);
        $this->assertEquals('butterfree-f', $pokemons[12]['pokemon_slug']);
        $this->assertEquals('butterfree-gmax', $pokemons[13]['pokemon_slug']);
        $this->assertEquals('rattata', $pokemons[14]['pokemon_slug']);
        $this->assertEquals('rattata-f', $pokemons[15]['pokemon_slug']);
        $this->assertEquals('raticate', $pokemons[16]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokemons[17]['pokemon_slug']);
        $this->assertEquals('raticate-alola-totem', $pokemons[18]['pokemon_slug']);
        $this->assertEquals('douze', $pokemons[19]['pokemon_slug']);

        $this->assertArrayHasKey('report', $data);

        /** @var int[]|int[][][]|string[][][] $report */
        $report = $data['report'];

        $this->assertReport($report, 9, 3, 1, 7, 20);
    }

    public function testGameBundleShinyAvailabilities(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'game_bundle_shiny_availabilities' => [
                    'ultrasunultramoon',
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

        $this->assertCount(4, $pokemons);
        $this->assertEquals('rattata-f', $pokemons[0]['pokemon_slug']);
        $this->assertEquals('rattata-alola', $pokemons[1]['pokemon_slug']);
        $this->assertEquals('raticate', $pokemons[2]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokemons[3]['pokemon_slug']);

        $this->assertArrayHasKey('report', $data);

        /** @var int[]|int[][][]|string[][][] $report */
        $report = $data['report'];

        $this->assertReport($report, 0, 2, 1, 1, 4);
    }

    public function testGameBundleShinyAvailabilitiesNull(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'game_bundle_shiny_availabilities' => [
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

    public function testGameBundleShinyAvailabilitiesNegative(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'game_bundle_shiny_availabilities' => [
                    '!ultrasunultramoon',
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

        $this->assertCount(18, $pokemons);

        $this->assertEquals('bulbasaur', $pokemons[0]['pokemon_slug']);
        $this->assertEquals('ivysaur', $pokemons[1]['pokemon_slug']);
        $this->assertEquals('venusaur', $pokemons[2]['pokemon_slug']);
        $this->assertEquals('venusaur-f', $pokemons[3]['pokemon_slug']);
        $this->assertEquals('venusaur-mega', $pokemons[4]['pokemon_slug']);
        $this->assertEquals('venusaur-gmax', $pokemons[5]['pokemon_slug']);
        $this->assertEquals('charmander', $pokemons[6]['pokemon_slug']);
        $this->assertEquals('charmeleon', $pokemons[7]['pokemon_slug']);
        $this->assertEquals('charizard', $pokemons[8]['pokemon_slug']);
        $this->assertEquals('caterpie', $pokemons[9]['pokemon_slug']);
        $this->assertEquals('metapod', $pokemons[10]['pokemon_slug']);
        $this->assertEquals('butterfree', $pokemons[11]['pokemon_slug']);
        $this->assertEquals('butterfree-f', $pokemons[12]['pokemon_slug']);
        $this->assertEquals('butterfree-gmax', $pokemons[13]['pokemon_slug']);
        $this->assertEquals('rattata', $pokemons[14]['pokemon_slug']);
        $this->assertEquals('raticate-alola', $pokemons[15]['pokemon_slug']);
        $this->assertEquals('raticate-alola-totem', $pokemons[16]['pokemon_slug']);
        $this->assertEquals('douze', $pokemons[17]['pokemon_slug']);

        $this->assertArrayHasKey('report', $data);

        /** @var int[]|int[][][]|string[][][] $report */
        $report = $data['report'];

        $this->assertReport($report, 9, 1, 2, 6, 18);
    }
}
