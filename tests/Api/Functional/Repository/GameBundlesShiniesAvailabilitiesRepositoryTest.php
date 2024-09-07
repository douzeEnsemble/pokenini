<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Entity\GameBundle;
use App\Api\Entity\GameBundleShinyAvailability;
use App\Api\Entity\Pokemon;
use App\Api\Repository\GameBundlesRepository;
use App\Api\Repository\GameBundlesShiniesAvailabilitiesRepository;
use App\Api\Repository\PokemonsRepository;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameBundleShinyAvailabilityTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class GameBundlesShiniesAvailabilitiesRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountGameBundleShinyAvailabilityTrait;

    /** @var GameBundle[] */
    private array $gameBundles;

    /** @var Pokemon[] */
    private array $pokemons;

    private GameBundlesShiniesAvailabilitiesRepository $gameBundleShinyAvailabilityRepo;
    private GameBundlesRepository $gameBundlesRepo;
    private PokemonsRepository $pokemonsRepo;

    public function setUp(): void
    {
        self::bootKernel();

        // Using temp variables is for avoid typing conflict
        /** @var GameBundlesShiniesAvailabilitiesRepository $gameBundleShinyAvailabilityRepo */
        $gameBundleShinyAvailabilityRepo = static::getContainer()
            ->get(GameBundlesShiniesAvailabilitiesRepository::class)
        ;
        $this->gameBundleShinyAvailabilityRepo = $gameBundleShinyAvailabilityRepo;

        /** @var GameBundlesRepository $gameBundlesRepo */
        $gameBundlesRepo = static::getContainer()->get(GameBundlesRepository::class);
        $this->gameBundlesRepo = $gameBundlesRepo;

        /** @var PokemonsRepository $pokemonsRepo */
        $pokemonsRepo = static::getContainer()->get(PokemonsRepository::class);
        $this->pokemonsRepo = $pokemonsRepo;
    }

    public function testRemoveAll(): void
    {
        $this->assertGreaterThan(0, $this->getGameBundleShinyAvailabilityCount());

        $this->gameBundleShinyAvailabilityRepo->removeAll();

        $this->assertEquals(0, $this->getGameBundleShinyAvailabilityCount());
    }

    public function testCalculate(): void
    {
        $this->gameBundleShinyAvailabilityRepo->removeAll();

        $this->assertEquals(9, $this->gameBundleShinyAvailabilityRepo->calculate());

        $this->assertIsNotAvailable('Red, Green, Blue, Yellow', 'douze');
        $this->assertIsNotAvailable('Gold, Silver, Crystal', 'douze');
        $this->assertIsNotAvailable('Ruby, Sapphire, Emerald', 'douze');

        $this->assertIsAvailable('Red, Green, Blue, Yellow', 'bulbasaur');
        $this->assertIsAvailable('Gold, Silver, Crystal', 'bulbasaur');
        $this->assertIsNotAvailable('Ruby, Sapphire, Emerald', 'bulbasaur');

        $this->assertIsAvailable('Red, Green, Blue, Yellow', 'ivysaur');
        $this->assertIsAvailable('Gold, Silver, Crystal', 'ivysaur');
        $this->assertIsNotAvailable('Ruby, Sapphire, Emerald', 'ivysaur');

        $this->assertIsAvailable('Red, Green, Blue, Yellow', 'venusaur');
        $this->assertIsAvailable('Gold, Silver, Crystal', 'venusaur');
        $this->assertIsNotAvailable('Ruby, Sapphire, Emerald', 'venusaur');

        $this->assertIsNotAvailable('Red, Green, Blue, Yellow', 'venusaur-mega');
        $this->assertIsNotAvailable('Gold, Silver, Crystal', 'venusaur-mega');
        $this->assertIsNotAvailable('Ruby, Sapphire, Emerald', 'venusaur-mega');
        $this->assertIsAvailable('X, Y', 'venusaur-mega');

        $this->assertIsNotAvailable('Red, Green, Blue, Yellow', 'deoxys');
        $this->assertIsNotAvailable('Gold, Silver, Crystal', 'deoxys');
        $this->assertIsAvailable('Ruby, Sapphire, Emerald', 'deoxys');

        $this->assertIsNotAvailable('Red, Green, Blue, Yellow', 'deoxys-attack');
        $this->assertIsNotAvailable('Gold, Silver, Crystal', 'deoxys-attack');
        $this->assertIsNotAvailable('Ruby, Sapphire, Emerald', 'deoxys-attack');
    }

    public function testGetFromPokemon(): void
    {
        $pokemonDouze = $this->getPokemon('douze');

        $listDouze = $this->gameBundleShinyAvailabilityRepo->getFromPokemon($pokemonDouze);
        $this->assertNull($listDouze->redgreenblueyellow);
        $this->assertNull($listDouze->goldsilvercrystal);

        $pokemonBulbasaur = $this->getPokemon('bulbasaur');

        $listBulbasaur = $this->gameBundleShinyAvailabilityRepo->getFromPokemon($pokemonBulbasaur);
        $this->assertTrue($listBulbasaur->redgreenblueyellow);
        $this->assertTrue($listBulbasaur->goldsilvercrystal);

        $pokemonMegaVenusaur = $this->getPokemon('venusaur-mega');

        $listMegaVenusaur = $this->gameBundleShinyAvailabilityRepo->getFromPokemon($pokemonMegaVenusaur);
        $this->assertFalse($listMegaVenusaur->redgreenblueyellow);
        $this->assertFalse($listMegaVenusaur->goldsilvercrystal);
    }

    private function assertIsAvailable(string $bundleName, string $pokemonSlug): void
    {
        $gameBundleShinyAvailability = $this->getGameBundleShinyAvailability($bundleName, $pokemonSlug);

        $this->assertTrue($gameBundleShinyAvailability?->isAvailable);
    }

    private function assertIsNotAvailable(string $bundleName, string $pokemonSlug): void
    {
        $gameBundleShinyAvailability = $this->getGameBundleShinyAvailability($bundleName, $pokemonSlug);

        $this->assertNotEquals(true, $gameBundleShinyAvailability?->isAvailable);
    }

    private function getGameBundleShinyAvailability(
        string $bundleName,
        string $pokemonSlug
    ): ?GameBundleShinyAvailability {
        $bundle = $this->getGameBundle($bundleName);
        $pokemon = $this->getPokemon($pokemonSlug);

        return $this->gameBundleShinyAvailabilityRepo->findOneBy([
            'pokemon' => $pokemon,
            'bundle' => $bundle,
        ]);
    }

    private function getGameBundle(string $name): GameBundle
    {
        if (isset($this->gameBundles[$name])) {
            return $this->gameBundles[$name];
        }

        /** @var GameBundle $gameBundle */
        $gameBundle = $this->gameBundlesRepo->findOneBy(['name' => $name]);

        $this->assertNotNull($gameBundle);

        $this->gameBundles[$name] = $gameBundle;

        return $gameBundle;
    }

    private function getPokemon(string $slug): Pokemon
    {
        if (isset($this->pokemons[$slug])) {
            return $this->pokemons[$slug];
        }

        /** @var Pokemon $pokemon */
        $pokemon = $this->pokemonsRepo->findOneBy(['slug' => $slug]);

        $this->assertNotNull($pokemon);

        $this->pokemons[$slug] = $pokemon;

        return $pokemon;
    }
}
