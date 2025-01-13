<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Entity\Pokemon;
use App\Api\Repository\PokemonsRepository;
use App\Tests\Api\Common\Traits\CounterTrait\CountPokemonTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(PokemonsRepository::class)]
class PokemonsRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountPokemonTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testRemoveAll(): void
    {
        $initCount = $this->getPokemonCount();
        $this->assertGreaterThan(0, $initCount);
        $this->assertEquals(0, $this->getPokemonDeletedCount());
        $this->assertGreaterThan(0, $this->getPokemonNotDeletedCount());

        /** @var PokemonsRepository $repo */
        $repo = static::getContainer()->get(PokemonsRepository::class);
        $repo->removeAll();

        $this->assertEquals($initCount, $this->getPokemonCount());
        $this->assertEquals($initCount, $this->getPokemonDeletedCount());
        $this->assertEquals(0, $this->getPokemonNotDeletedCount());
    }

    public function testGetAll(): void
    {
        /** @var PokemonsRepository $repo */
        $repo = static::getContainer()->get(PokemonsRepository::class);

        /** @var Pokemon[] $pokemons */
        $pokemons = $repo->getQueryAll()->getResult();

        $this->assertCount($this->getPokemonCount(), $pokemons);

        $this->assertEquals('Bulbasaur', $pokemons[0]->name);
        $this->assertEquals(1, $pokemons[0]->nationalDexNumber);
        $this->assertEquals('bulbasaur', $pokemons[0]->family);

        $this->assertTrue($pokemons[3]->bankable);
        $this->assertNull($pokemons[3]->bankableish);
        $this->assertEquals('Diamond, Pearl, Platinium', $pokemons[3]->originalGameBundle->name);

        $this->assertNull($pokemons[5]->variantForm);
        $this->assertNull($pokemons[5]->regionalForm);
        $this->assertEquals('Gigantamax', $pokemons[5]->specialForm?->name);

        $this->assertEquals('charizard', $pokemons[8]->iconName);
        $this->assertEquals('butterfree', $pokemons[11]->iconName);

        $this->assertEquals('fire', $pokemons[8]->primaryType?->slug);
        $this->assertEquals('flying', $pokemons[8]->secondaryType?->slug);
    }

    public function testCountAll(): void
    {
        /** @var PokemonsRepository $repo */
        $repo = static::getContainer()->get(PokemonsRepository::class);

        $this->assertEquals($this->getPokemonCount(), $repo->countAll());
    }

    #[DataProvider('providergetNToPick')]
    public function testgetNToPick(
        string $dexSlug,
        string $electionSlug,
        int $expectedCount,
    ): void {
        /** @var PokemonsRepository $repo */
        $repo = static::getContainer()->get(PokemonsRepository::class);

        $list = $repo->getNToPick(
            $dexSlug,
            12,
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            $electionSlug,
            1000,
        );

        $this->assertCount($expectedCount, $list);

        $previous = $list[0]['pokemon_slug'];
        $max = count($list);
        for ($i = 1; $i < $max; ++$i) {
            $this->assertNotSame($previous, $list[$i]['pokemon_slug']);

            $previous = $list[$i]['pokemon_slug'];
        }
    }

    /**
     * @return int[][]|string[][]
     */
    public static function providergetNToPick(): array
    {
        return [
            'home-12' => [
                'dexSlug' => 'home',
                'electionSlug' => '',
                'expectedCount' => 12,
            ],
            'redgreenblueyellow-12' => [
                'dexSlug' => 'redgreenblueyellow',
                'electionSlug' => '',
                'expectedCount' => 7,
            ],
            'demo-affinee-12-10-10' => [
                'dexSlug' => 'redgreenblueyellow',
                'electionSlug' => 'affinee',
                'expectedCount' => 1,
            ],
            'demo-affinee-12-2--1' => [
                'dexSlug' => 'redgreenblueyellow',
                'electionSlug' => 'affinee',
                'expectedCount' => 1,
            ],
        ];
    }

    #[DataProvider('providergetNToVote')]
    public function testgetNToVote(
        string $dexSlug,
        string $electionSlug,
        int $expectedCount,
    ): void {
        /** @var PokemonsRepository $repo */
        $repo = static::getContainer()->get(PokemonsRepository::class);

        $list = $repo->getNToVote(
            $dexSlug,
            12,
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            $electionSlug,
            1000,
        );

        $this->assertCount($expectedCount, $list);

        $previous = $list[0]['pokemon_slug'];
        $max = count($list);
        for ($i = 1; $i < $max; ++$i) {
            $this->assertNotSame($previous, $list[$i]['pokemon_slug']);

            $previous = $list[$i]['pokemon_slug'];
        }
    }

    /**
     * @return int[][]|string[][]
     */
    public static function providergetNToVote(): array
    {
        return [
            'home-12' => [
                'dexSlug' => 'home',
                'electionSlug' => '',
                'expectedCount' => 0,
            ],
            'redgreenblueyellow-12' => [
                'dexSlug' => 'redgreenblueyellow',
                'electionSlug' => '',
                'expectedCount' => 0,
            ],
            'demo-affinee-12-10-10' => [
                'dexSlug' => 'redgreenblueyellow',
                'electionSlug' => 'affinee',
                'expectedCount' => 2,
            ],
            'demo-affinee-12-2--1' => [
                'dexSlug' => 'redgreenblueyellow',
                'electionSlug' => 'affinee',
                'expectedCount' => 2,
            ],
        ];
    }
}
