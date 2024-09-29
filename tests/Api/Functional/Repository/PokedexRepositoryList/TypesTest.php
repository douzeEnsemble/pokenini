<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository\PokedexRepositoryList;

use App\Api\DTO\AlbumFilter\AlbumFilters;
use App\Api\Repository\PokedexRepository;
use App\Api\Repository\Trait\FiltersTrait;
use App\Tests\Api\Common\Traits\GetterTrait\GetPokedexTrait;
use App\Tests\Api\Common\Traits\PokemonListTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(PokedexRepository::class)]
#[CoversTrait(FiltersTrait::class)]
class TypesTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use GetPokedexTrait;
    use DataTrait;
    use PokemonListTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetListQueryPrimaryTypeFilter(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'primaryTypes' => [
                    'grass',
                ],
            ])
        );

        $this->assertSameSlugs(
            $pokedex,
            [
                'bulbasaur',
                'ivysaur',
                'venusaur',
                'venusaur-f',
                'venusaur-mega',
                'venusaur-gmax',
            ],
        );
    }

    public function testGetListQuerySecondaryTypeFilter(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'secondaryTypes' => [
                    'normal',
                ],
            ])
        );

        $this->assertSameSlugs(
            $pokedex,
            [
                'rattata-alola',
                'raticate-alola',
                'raticate-alola-totem',
            ],
        );
    }

    public function testGetListQueryPrimaryAndSecondaryTypeFilter(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'primaryTypes' => [
                    'bug',
                ],
                'secondaryTypes' => [
                    'flying',
                ],
            ])
        );

        $this->assertSameSlugs(
            $pokedex,
            [
                'butterfree',
                'butterfree-f',
                'butterfree-gmax',
            ],
        );
    }

    public function testGetListQueryAnyTypeFilter(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'anyTypes' => [
                    'normal',
                ],
            ])
        );

        $this->assertSameSlugs(
            $pokedex,
            [
                'rattata',
                'rattata-f',
                'rattata-alola',
                'raticate',
                'raticate-f',
                'raticate-alola',
                'raticate-alola-totem',
            ],
        );
    }
}
