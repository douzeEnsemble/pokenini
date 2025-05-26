<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Entity\Pokemon;
use App\Api\Repository\CollectionsAvailabilitiesRepository;
use App\Api\Repository\PokemonsRepository;
use App\Tests\Api\Common\Traits\CounterTrait\CountCollectionAvailabilityTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(CollectionsAvailabilitiesRepository::class)]
class CollectionsAvailabilitiesRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountCollectionAvailabilityTrait;

    /** @var Pokemon[] */
    private array $pokemons;

    private CollectionsAvailabilitiesRepository $collectionsAvailabilitiesRepo;
    private PokemonsRepository $pokemonsRepo;

    #[\Override]
    public function setUp(): void
    {
        self::bootKernel();

        // Using temp variables is for avoid typing conflict
        /** @var CollectionsAvailabilitiesRepository $collectionsAvailabilitiesRepo */
        $collectionsAvailabilitiesRepo = static::getContainer()->get(CollectionsAvailabilitiesRepository::class);
        $this->collectionsAvailabilitiesRepo = $collectionsAvailabilitiesRepo;

        /** @var PokemonsRepository $pokemonsRepo */
        $pokemonsRepo = static::getContainer()->get(PokemonsRepository::class);
        $this->pokemonsRepo = $pokemonsRepo;
    }

    public function testRemoveAll(): void
    {
        $this->assertGreaterThan(0, $this->getCollectionAvailabilityCount());

        $this->collectionsAvailabilitiesRepo->removeAll();

        $this->assertEquals(0, $this->getCollectionAvailabilityCount());
    }

    public function testGetFromPokemon(): void
    {
        $pokemonDouze = $this->getPokemon('Douze');

        $listDouze = $this->collectionsAvailabilitiesRepo->getFromPokemon($pokemonDouze);
        $this->assertNull($listDouze->nexistepas);
        $this->assertTrue($listDouze->pogodynamax);
        $this->assertFalse($listDouze->pogoshadow);

        $pokemonBulbasaur = $this->getPokemon('Bulbasaur');

        $listBulbasaur = $this->collectionsAvailabilitiesRepo->getFromPokemon($pokemonBulbasaur);
        $this->assertNull($listBulbasaur->nexistepas);
        $this->assertFalse($listBulbasaur->pogodynamax);
        $this->assertTrue($listBulbasaur->pogoshadow);
    }

    private function getPokemon(string $name): Pokemon
    {
        if (isset($this->pokemons[$name])) {
            return $this->pokemons[$name];
        }

        /** @var ?Pokemon $pokemon */
        $pokemon = $this->pokemonsRepo->findOneBy(['name' => $name]);

        $this->assertNotNull($pokemon);

        $this->pokemons[$name] = $pokemon;

        return $pokemon;
    }
}
