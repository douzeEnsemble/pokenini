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
class FamiliesTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use GetPokedexTrait;
    use DataTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetListQueryFamilies(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'families' => [
                    'bulbasaur',
                ],
            ])
        );

        $this->assertCount(6, $pokedex);
        $this->assertEquals('bulbasaur', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('ivysaur', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('venusaur', $pokedex[2]['pokemon_slug']);
        $this->assertEquals('venusaur-f', $pokedex[3]['pokemon_slug']);
        $this->assertEquals('venusaur-mega', $pokedex[4]['pokemon_slug']);
        $this->assertEquals('venusaur-gmax', $pokedex[5]['pokemon_slug']);
    }
}
