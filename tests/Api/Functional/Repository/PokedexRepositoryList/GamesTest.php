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
class GamesTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use GetPokedexTrait;
    use DataTrait;
    use PokemonListTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetListQueryOriginalGameBundle(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'originalGameBundles' => [
                    'redgreenblueyellow',
                ],
            ])
        );

        $this->assertSameSlugs(
            $pokedex,
            [
                'bulbasaur',
                'ivysaur',
                'venusaur',
                'charmander',
                'charmeleon',
                'charizard',
                'caterpie',
                'metapod',
                'butterfree',
                'rattata',
                'raticate',
                'douze',
            ],
        );
    }

    public function testGetListQueryGameBundleAvailabilities(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'gameBundleAvailabilities' => [
                    'ultrasunultramoon',
                ],
            ])
        );

        $this->assertSameSlugs(
            $pokedex,
            [
                'rattata-alola',
                'raticate-alola',
            ],
        );
    }

    public function testGetListQueryGameBundleAvailabilitiesNegative(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'gameBundleAvailabilities' => [
                    '!ultrasunultramoon',
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
                'charmander',
                'charmeleon',
                'charizard',
                'caterpie',
                'metapod',
                'butterfree',
                'butterfree-f',
                'butterfree-gmax',
                'rattata',
                'rattata-f',
                'raticate',
                'raticate-f',
                'raticate-alola-totem',
                'douze',
            ],
        );
    }

    public function testGetListQueryGameBundleShinyAvailabilities(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'gameBundleShinyAvailabilities' => [
                    'ultrasunultramoon',
                ],
            ])
        );

        $this->assertSameSlugs(
            $pokedex,
            [
                'rattata-f',
                'rattata-alola',
                'raticate',
                'raticate-f',
            ],
        );
    }

    public function testGetListQueryGameBundleShinyAvailabilitiesNegative(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'gameBundleShinyAvailabilities' => [
                    '!ultrasunultramoon',
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
                'charmander',
                'charmeleon',
                'charizard',
                'caterpie',
                'metapod',
                'butterfree',
                'butterfree-f',
                'butterfree-gmax',
                'rattata',
                'raticate-alola',
                'raticate-alola-totem',
                'douze',
            ],
        );
    }
}
