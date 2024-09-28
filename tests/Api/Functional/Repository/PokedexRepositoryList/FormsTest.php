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
class FormsTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use GetPokedexTrait;
    use DataTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetListQueryCategoryForm(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'categoryForms' => [
                    'starter',
                ],
            ])
        );

        $this->assertCount(2, $pokedex);
        $this->assertEquals('bulbasaur', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('charmander', $pokedex[1]['pokemon_slug']);
    }

    public function testGetListQueryRegionalForm(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'regionalForms' => [
                    'alolan',
                ],
            ])
        );

        $this->assertCount(3, $pokedex);
        $this->assertEquals('rattata-alola', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('raticate-alola', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('raticate-alola-totem', $pokedex[2]['pokemon_slug']);
    }

    public function testGetListQuerySpecialForm(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'specialForms' => [
                    'gigantamax',
                ],
            ])
        );

        $this->assertCount(2, $pokedex);
        $this->assertEquals('venusaur-gmax', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('butterfree-gmax', $pokedex[1]['pokemon_slug']);
    }

    public function testGetListQuerySpecialsForm(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'specialForms' => [
                    'gigantamax',
                    'mega',
                ],
            ])
        );

        $this->assertCount(3, $pokedex);
        $this->assertEquals('venusaur-mega', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('venusaur-gmax', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('butterfree-gmax', $pokedex[2]['pokemon_slug']);
    }

    public function testGetListQueryVariantForm(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'variantForms' => [
                    'gender',
                ],
            ])
        );

        $this->assertCount(4, $pokedex);
        $this->assertEquals('venusaur-f', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('butterfree-f', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('rattata-f', $pokedex[2]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokedex[3]['pokemon_slug']);
    }
}
