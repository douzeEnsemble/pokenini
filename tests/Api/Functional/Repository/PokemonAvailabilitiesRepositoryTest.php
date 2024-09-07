<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\PokemonAvailabilitiesRepository;
use App\Tests\Api\Common\Traits\CounterTrait\CountPokemonAvailabilitiesTrait;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class PokemonAvailabilitiesRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountPokemonAvailabilitiesTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testRemoveAllByCategory(): void
    {
        $this->assertGreaterThan(0, $this->getPokemonAvailabilitiesCount('game_bundle'));
        $previousCount = $this->getPokemonAvailabilitiesCount('game_bundle_shiny');

        /** @var PokemonAvailabilitiesRepository $repo */
        $repo = static::getContainer()->get(PokemonAvailabilitiesRepository::class);

        $repo->removeAllByCategory('game_bundle');

        $this->assertEquals(0, $this->getPokemonAvailabilitiesCount('game_bundle'));
        $this->assertEquals(
            $previousCount,
            $this->getPokemonAvailabilitiesCount('game_bundle_shiny')
        );
    }

    public function testCalculateGameBundle(): void
    {
        /** @var PokemonAvailabilitiesRepository $repo */
        $repo = static::getContainer()->get(PokemonAvailabilitiesRepository::class);

        // Clean the database
        $repo->removeAllByCategory('game_bundle');

        $previousCount = $this->getPokemonAvailabilitiesCount('game_bundle_shiny');

        $count = $repo->calculateGameBundle();

        $this->assertGreaterThan(0, $count);
        $this->assertEquals($count, $this->getPokemonAvailabilitiesCount('game_bundle'));
        $this->assertEquals(
            $previousCount,
            $this->getPokemonAvailabilitiesCount('game_bundle_shiny')
        );
    }

    public function testCalculateGameBundleShiny(): void
    {
        /** @var PokemonAvailabilitiesRepository $repo */
        $repo = static::getContainer()->get(PokemonAvailabilitiesRepository::class);

        // Clean the database
        $repo->removeAllByCategory('game_bundle_shiny');

        $previousCount = $this->getPokemonAvailabilitiesCount('game_bundle');

        $count = $repo->calculateGameBundleShiny();

        $this->assertGreaterThan(0, $count);
        $this->assertEquals($count, $this->getPokemonAvailabilitiesCount('game_bundle_shiny'));
        $this->assertEquals(
            $previousCount,
            $this->getPokemonAvailabilitiesCount('game_bundle')
        );
    }

    public function testCalculateUnicity(): void
    {
        /** @var PokemonAvailabilitiesRepository $repo */
        $repo = static::getContainer()->get(PokemonAvailabilitiesRepository::class);

        $this->expectException(UniqueConstraintViolationException::class);
        $this->expectExceptionMessageMatches('/duplicate key value violates unique constraint/');

        $repo->calculateGameBundle();
    }
}
