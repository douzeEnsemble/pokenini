<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository\PokedexRepositoryList;

use App\Api\DTO\AlbumFilter\AlbumFilters;
use App\Api\Repository\PokedexRepository;
use App\Api\Repository\Trait\FiltersTrait;
use App\Tests\Api\Common\Traits\GetterTrait\GetPokedexTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(PokedexRepository::class)]
#[CoversTrait(FiltersTrait::class)]
class CollectionsTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use GetPokedexTrait;
    use DataTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetListQueryCollectionAvailabilities(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'collectionAvailabilities' => [
                    'pogoshadow',
                ],
            ])
        );

        $this->assertCount(1, $pokedex);

        $this->assertEquals('bulbasaur', $pokedex[0]['pokemon_slug']);
    }

    public function testGetListQueryCollectionAvailabilitiesNegative(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'collectionAvailabilities' => [
                    '!pogoshadow',
                ],
            ])
        );

        $this->assertCount(22, $pokedex);

        $this->assertEquals('bulbasaur', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('ivysaur', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('venusaur', $pokedex[2]['pokemon_slug']);
        $this->assertEquals('venusaur-f', $pokedex[3]['pokemon_slug']);
        $this->assertEquals('venusaur-mega', $pokedex[4]['pokemon_slug']);
        $this->assertEquals('venusaur-gmax', $pokedex[5]['pokemon_slug']);
        $this->assertEquals('charmander', $pokedex[6]['pokemon_slug']);
        $this->assertEquals('charmeleon', $pokedex[7]['pokemon_slug']);
        $this->assertEquals('charizard', $pokedex[8]['pokemon_slug']);
        $this->assertEquals('caterpie', $pokedex[9]['pokemon_slug']);
        $this->assertEquals('metapod', $pokedex[10]['pokemon_slug']);
        $this->assertEquals('butterfree', $pokedex[11]['pokemon_slug']);
        $this->assertEquals('butterfree-f', $pokedex[12]['pokemon_slug']);
        $this->assertEquals('butterfree-gmax', $pokedex[13]['pokemon_slug']);
        $this->assertEquals('rattata', $pokedex[14]['pokemon_slug']);
        $this->assertEquals('rattata-f', $pokedex[15]['pokemon_slug']);
        $this->assertEquals('rattata-alola', $pokedex[16]['pokemon_slug']);
        $this->assertEquals('raticate', $pokedex[17]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokedex[18]['pokemon_slug']);
        $this->assertEquals('raticate-alola', $pokedex[19]['pokemon_slug']);
        $this->assertEquals('raticate-alola-totem', $pokedex[20]['pokemon_slug']);
        $this->assertEquals('douze', $pokedex[21]['pokemon_slug']);
    }
}
