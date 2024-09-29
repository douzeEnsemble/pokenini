<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Service\Album;

use App\Api\DTO\AlbumFilter\AlbumFilters;
use App\Api\Service\Album\AlbumPokemonService;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameBundleAvailabilityTrait;
use App\Tests\Api\Common\Traits\PokemonListTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(AlbumPokemonService::class)]
class AlbumPokemonServiceFilteredTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountGameBundleAvailabilityTrait;
    use PokemonListTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testListFilteredPrimaryType(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'primaryTypes' => [
                    'grass',
                ],
            ]),
        );

        $this->assertCount(6, $pokemons);

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
    }

    public function testListFilteredSecondaryType(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'secondaryTypes' => [
                    'normal',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'rattata-alola',
                'raticate-alola',
                'raticate-alola-totem',
            ],
        );
    }

    public function testListFilteredPrimaryAndSecondaryType(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'primaryTypes' => [
                    'bug',
                ],
                'secondaryTypes' => [
                    'flying',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'butterfree',
                'butterfree-f',
                'butterfree-gmax',
            ],
        );
    }

    public function testListFilteredAnyType(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'anyTypes' => [
                    'normal',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
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

    public function testListFilteredCategoryType(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'categoryForms' => [
                    'starter',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'bulbasaur',
                'charmander',
            ],
        );
    }

    public function testListFilteredRegionalType(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'regionalForms' => [
                    'alolan',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'rattata-alola',
                'raticate-alola',
                'raticate-alola-totem',
            ],
        );
    }

    public function testListFilteredSpecialType(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'specialForms' => [
                    'gigantamax',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'venusaur-gmax',
                'butterfree-gmax',
            ],
        );
    }

    public function testListFilteredSpecialsType(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'specialForms' => [
                    'gigantamax',
                    'mega',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'venusaur-mega',
                'venusaur-gmax',
                'butterfree-gmax',
            ],
        );
    }

    public function testListFilteredVariantType(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'variantForms' => [
                    'gender',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'venusaur-f',
                'butterfree-f',
                'rattata-f',
                'raticate-f',
            ],
        );
    }

    public function testListFilteredCatchStates(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'catchStates' => [
                    'maybe',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'caterpie',
                'rattata-f',
                'raticate-f',
            ],
        );
    }

    public function testListFilteredOriginalGameBundle(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'originalGameBundles' => [
                    'redgreenblueyellow',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
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

    public function testListFilteredGameBundleAvailabilities(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'gameBundleAvailabilities' => [
                    'ultrasunultramoon',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'rattata-alola',
                'raticate-alola',
            ],
        );
    }

    public function testListFilteredGameBundleShinyAvailabilities(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'gameBundleShinyAvailabilities' => [
                    'ultrasunultramoon',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'rattata-f',
                'rattata-alola',
                'raticate',
                'raticate-f',
            ],
        );
    }

    public function testListFilteredFamilies(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'families' => [
                    'bulbasaur',
                ],
            ]),
        );

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
    }

    public function testListFilteredCollections(): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'collectionAvailabilities' => [
                    'pogoshadow',
                ],
            ]),
        );

        $this->assertSameSlugs(
            $pokemons,
            [
                'bulbasaur',
            ],
        );
    }

    #[DataProvider('providerListFilteredNull')]
    public function testListFilteredNull(string $filter, int $expectedCount): void
    {
        /** @var AlbumPokemonService $service */
        $service = static::getContainer()->get(AlbumPokemonService::class);

        $pokemons = $service->get(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                $filter => [
                    'null',
                ],
            ]),
        );

        $this->assertCount($expectedCount, $pokemons);
    }

    /**
     * @return int[][]|string[][]
     */
    public static function providerListFilteredNull(): array
    {
        return [
            'primary_types' => [
                'primaryTypes',
                1,
            ],
            'secondary_types' => [
                'secondaryTypes',
                9,
            ],
            'category_forms' => [
                'categoryForms',
                20,
            ],
            'regional_forms' => [
                'regionalForms',
                19,
            ],
            'special_forms' => [
                'specialForms',
                18,
            ],
            'variant_forms' => [
                'variantForms',
                18,
            ],
            'catch_states' => [
                'catchStates',
                1,
            ],
            'original_game_bundles' => [
                'originalGameBundles',
                0,
            ],
            'game_bundle_availabilities' => [
                'gameBundleAvailabilities',
                0,
            ],
            'game_bundle_shiny_availabilities' => [
                'gameBundleShinyAvailabilities',
                0,
            ],
            'collection_availabilities' => [
                'collectionAvailabilities',
                0,
            ],
        ];
    }
}
