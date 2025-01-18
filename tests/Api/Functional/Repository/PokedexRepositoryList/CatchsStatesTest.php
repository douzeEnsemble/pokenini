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
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(PokedexRepository::class)]
#[CoversClass(FiltersTrait::class)]
class CatchsStatesTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use GetPokedexTrait;
    use DataTrait;
    use PokemonListTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetListQueryCatchStates(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'catch_states' => [
                    'maybe',
                ],
            ])
        );

        $this->assertSameSlugs(
            $pokedex,
            [
                'caterpie',
                'rattata-f',
                'raticate-f',
            ],
        );
    }

    public function testGetListQueryCatchStatesNegative(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'catch_states' => [
                    '!maybe',
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
                'metapod',
                'butterfree',
                'butterfree-f',
                'butterfree-gmax',
                'rattata',
                'rattata-alola',
                'raticate',
                'raticate-alola',
                'raticate-alola-totem',
                'douze',
            ],
        );
    }

    public function testGetListQueryCatchStatesNegativeNo(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'catch_states' => [
                    '!no',
                ],
            ])
        );

        $this->assertSameSlugs(
            $pokedex,
            [
                'charmander',
                'charmeleon',
                'charizard',
                'caterpie',
                'metapod',
                'butterfree',
                'butterfree-f',
                'rattata',
                'rattata-f',
                'rattata-alola',
                'raticate',
                'raticate-f',
                'raticate-alola',
            ],
        );
    }
}
