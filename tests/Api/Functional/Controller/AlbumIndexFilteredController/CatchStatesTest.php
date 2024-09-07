<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller\AlbumIndexFilteredController;

use App\Tests\Api\Common\Traits\ReportTrait\AssertReportTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class CatchStatesTest extends AbstractTestAlbumIndexFilteredController
{
    use AssertReportTrait;

    public function testCatchStateFilter(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'catch_states' => [
                    'maybe',
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

        $this->assertCount(3, $pokemons);
        $this->assertEquals('caterpie', $pokemons[0]['pokemon_slug']);
        $this->assertEquals('rattata-f', $pokemons[1]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokemons[2]['pokemon_slug']);

        $this->assertArrayHasKey('report', $data);

        /** @var int[]|int[][][]|string[][][] $report */
        $report = $data['report'];

        $this->assertReport($report, 0, 3, 0, 0, 3);
    }

    public function testNoCatchStateFilter(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'catch_states' => [
                    'no',
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

        $this->assertCount(9, $pokemons);

        $this->assertArrayHasKey('report', $data);

        /** @var int[]|int[][][]|string[][][] $report */
        $report = $data['report'];

        $this->assertReport($report, 9, 0, 0, 0, 9);
    }

    public function testCatchStateFilterNull(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'catch_states' => [
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

        $this->assertCount(1, $pokemons);
    }

    public function testCatchStatesFilter(): void
    {
        $this->apiRequest(
            'GET',
            'api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home',
            [
                'catch_states' => [
                    'maybe',
                    'maybenot',
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

        $this->assertCount(6, $pokemons);
        $this->assertEquals('caterpie', $pokemons[0]['pokemon_slug']);
        $this->assertEquals('metapod', $pokemons[1]['pokemon_slug']);
        $this->assertEquals('rattata-f', $pokemons[2]['pokemon_slug']);
        $this->assertEquals('rattata-alola', $pokemons[3]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokemons[4]['pokemon_slug']);
        $this->assertEquals('raticate-alola', $pokemons[5]['pokemon_slug']);

        $this->assertArrayHasKey('report', $data);

        /** @var int[]|int[][][]|string[][][] $report */
        $report = $data['report'];

        $this->assertReport($report, 0, 3, 3, 0, 6);
    }
}
