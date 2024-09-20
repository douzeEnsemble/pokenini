<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

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
class PokedexRepositoryListTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use GetPokedexTrait;
    use PokedexRepositoryListDataTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetListQuery(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'redgreenblueyellow',
            AlbumFilters::createFromArray([]),
        );

        $this->assertCount(7, $pokedex);

        $this->assertPokedexBulbasaur($pokedex);
        $this->assertPokedexIvysaur($pokedex);
        $this->assertPokedexVenusaur($pokedex);
        $this->assertPokedexCaterpie($pokedex);
        $this->assertPokedexDouze($pokedex);
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

        $this->assertCount(6, $pokedex);
        $this->assertEquals('bulbasaur', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('ivysaur', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('venusaur', $pokedex[2]['pokemon_slug']);
        $this->assertEquals('venusaur-f', $pokedex[3]['pokemon_slug']);
        $this->assertEquals('venusaur-mega', $pokedex[4]['pokemon_slug']);
        $this->assertEquals('venusaur-gmax', $pokedex[5]['pokemon_slug']);
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

        $this->assertCount(3, $pokedex);
        $this->assertEquals('rattata-alola', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('raticate-alola', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('raticate-alola-totem', $pokedex[2]['pokemon_slug']);
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

        $this->assertCount(3, $pokedex);
        $this->assertEquals('butterfree', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('butterfree-f', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('butterfree-gmax', $pokedex[2]['pokemon_slug']);
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

        $this->assertCount(7, $pokedex);
        $this->assertEquals('rattata', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('rattata-f', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('rattata-alola', $pokedex[2]['pokemon_slug']);
        $this->assertEquals('raticate', $pokedex[3]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokedex[4]['pokemon_slug']);
        $this->assertEquals('raticate-alola', $pokedex[5]['pokemon_slug']);
        $this->assertEquals('raticate-alola-totem', $pokedex[6]['pokemon_slug']);
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

    public function testGetListQueryCatchStates(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'catchStates' => [
                    'maybe',
                ],
            ])
        );

        $this->assertCount(3, $pokedex);
        $this->assertEquals('caterpie', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('rattata-f', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokedex[2]['pokemon_slug']);
    }

    public function testGetListQueryCatchStatesNegative(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'catchStates' => [
                    '!maybe',
                ],
            ])
        );

        $this->assertCount(18, $pokedex);

        $this->assertEquals('bulbasaur', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('ivysaur', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('venusaur', $pokedex[2]['pokemon_slug']);
        $this->assertEquals('venusaur-f', $pokedex[3]['pokemon_slug']);
        $this->assertEquals('venusaur-mega', $pokedex[4]['pokemon_slug']);
        $this->assertEquals('venusaur-gmax', $pokedex[5]['pokemon_slug']);
        $this->assertEquals('charmander', $pokedex[6]['pokemon_slug']);
        $this->assertEquals('charmeleon', $pokedex[7]['pokemon_slug']);
        $this->assertEquals('charizard', $pokedex[8]['pokemon_slug']);
        $this->assertEquals('metapod', $pokedex[9]['pokemon_slug']);
        $this->assertEquals('butterfree', $pokedex[10]['pokemon_slug']);
        $this->assertEquals('butterfree-f', $pokedex[11]['pokemon_slug']);
        $this->assertEquals('butterfree-gmax', $pokedex[12]['pokemon_slug']);
        $this->assertEquals('rattata', $pokedex[13]['pokemon_slug']);
        $this->assertEquals('rattata-alola', $pokedex[14]['pokemon_slug']);
        $this->assertEquals('raticate', $pokedex[15]['pokemon_slug']);
        $this->assertEquals('raticate-alola', $pokedex[16]['pokemon_slug']);
        $this->assertEquals('raticate-alola-totem', $pokedex[17]['pokemon_slug']);
    }

    public function testGetListQueryCatchStatesNegativeNo(): void
    {
        /** @var PokedexRepository $repo */
        $repo = static::getContainer()->get(PokedexRepository::class);

        $pokedex = $repo->getList(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'home',
            AlbumFilters::createFromArray([
                'catchStates' => [
                    '!no',
                ],
            ])
        );

        $this->assertCount(13, $pokedex);

        $this->assertEquals('charmander', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('charmeleon', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('charizard', $pokedex[2]['pokemon_slug']);
        $this->assertEquals('caterpie', $pokedex[3]['pokemon_slug']);
        $this->assertEquals('metapod', $pokedex[4]['pokemon_slug']);
        $this->assertEquals('butterfree', $pokedex[5]['pokemon_slug']);
        $this->assertEquals('butterfree-f', $pokedex[6]['pokemon_slug']);
        $this->assertEquals('rattata', $pokedex[7]['pokemon_slug']);
        $this->assertEquals('rattata-f', $pokedex[8]['pokemon_slug']);
        $this->assertEquals('rattata-alola', $pokedex[9]['pokemon_slug']);
        $this->assertEquals('raticate', $pokedex[10]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokedex[11]['pokemon_slug']);
        $this->assertEquals('raticate-alola', $pokedex[12]['pokemon_slug']);
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

        $this->assertCount(12, $pokedex);
        $this->assertEquals('bulbasaur', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('ivysaur', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('venusaur', $pokedex[2]['pokemon_slug']);
        $this->assertEquals('charmander', $pokedex[3]['pokemon_slug']);
        $this->assertEquals('charmeleon', $pokedex[4]['pokemon_slug']);
        $this->assertEquals('charizard', $pokedex[5]['pokemon_slug']);
        $this->assertEquals('caterpie', $pokedex[6]['pokemon_slug']);
        $this->assertEquals('metapod', $pokedex[7]['pokemon_slug']);
        $this->assertEquals('butterfree', $pokedex[8]['pokemon_slug']);
        $this->assertEquals('rattata', $pokedex[9]['pokemon_slug']);
        $this->assertEquals('raticate', $pokedex[10]['pokemon_slug']);
        $this->assertEquals('douze', $pokedex[11]['pokemon_slug']);
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

        $this->assertCount(2, $pokedex);
        $this->assertEquals('rattata-alola', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('raticate-alola', $pokedex[1]['pokemon_slug']);
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

        $this->assertCount(20, $pokedex);

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
        $this->assertEquals('raticate', $pokedex[16]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokedex[17]['pokemon_slug']);
        $this->assertEquals('raticate-alola-totem', $pokedex[18]['pokemon_slug']);
        $this->assertEquals('douze', $pokedex[19]['pokemon_slug']);
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

        $this->assertCount(4, $pokedex);
        $this->assertEquals('rattata-f', $pokedex[0]['pokemon_slug']);
        $this->assertEquals('rattata-alola', $pokedex[1]['pokemon_slug']);
        $this->assertEquals('raticate', $pokedex[2]['pokemon_slug']);
        $this->assertEquals('raticate-f', $pokedex[3]['pokemon_slug']);
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

        $this->assertCount(18, $pokedex);

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
        $this->assertEquals('raticate-alola', $pokedex[15]['pokemon_slug']);
        $this->assertEquals('raticate-alola-totem', $pokedex[16]['pokemon_slug']);
        $this->assertEquals('douze', $pokedex[17]['pokemon_slug']);
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
