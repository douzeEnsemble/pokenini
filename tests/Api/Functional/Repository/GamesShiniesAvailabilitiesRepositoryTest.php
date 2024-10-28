<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Entity\Pokemon;
use App\Api\Repository\GamesShiniesAvailabilitiesRepository;
use App\Api\Repository\PokemonsRepository;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameShinyAvailabilityTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(GamesShiniesAvailabilitiesRepository::class)]
class GamesShiniesAvailabilitiesRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountGameShinyAvailabilityTrait;

    /** @var Pokemon[] */
    private array $pokemons;

    private GamesShiniesAvailabilitiesRepository $gamesShiniesAvailabilitiesRepo;
    private PokemonsRepository $pokemonsRepo;

    public function setUp(): void
    {
        self::bootKernel();

        // Using temp variables is for avoid typing conflict
        /** @var GamesShiniesAvailabilitiesRepository $gamesShiniesAvailabilitiesRepo */
        $gamesShiniesAvailabilitiesRepo = static::getContainer()->get(GamesShiniesAvailabilitiesRepository::class);
        $this->gamesShiniesAvailabilitiesRepo = $gamesShiniesAvailabilitiesRepo;

        /** @var PokemonsRepository $pokemonsRepo */
        $pokemonsRepo = static::getContainer()->get(PokemonsRepository::class);
        $this->pokemonsRepo = $pokemonsRepo;
    }

    public function testRemoveAll(): void
    {
        $this->assertGreaterThan(0, $this->getGameShinyAvailabilityCount());

        $this->gamesShiniesAvailabilitiesRepo->removeAll();

        $this->assertEquals(0, $this->getGameShinyAvailabilityCount());
    }

    public function testGetFromPokemon(): void
    {
        $pokemonDeoxys = $this->getPokemon('Deoxys');

        $listDeoxys = $this->gamesShiniesAvailabilitiesRepo->getFromPokemon($pokemonDeoxys);
        $this->assertNull($listDeoxys->nexistepas);
        $this->assertTrue($listDeoxys->ruby);
        $this->assertFalse($listDeoxys->emerald);
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
