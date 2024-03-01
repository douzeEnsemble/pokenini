<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Api\Repository\PokemonsRepository;
use App\Tests\Api\Common\Traits\CounterTrait\CountPokemonAvailabilitiesTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountPokemonTrait;
use App\Tests\Api\Common\Traits\HasserTrait\HasPokemonAvailabilitiesTrait;

class CalculatePokemonAvailabilitiesCommandTest extends AbstractTestCaseCommand
{
    use CountPokemonTrait;
    use CountPokemonAvailabilitiesTrait;
    use HasPokemonAvailabilitiesTrait;
    use CountActionLogTrait;

    public function testNoPokemonAvailabilities(): void
    {
        /** @var PokemonsRepository $repo */
        $repo = static::getContainer()->get(PokemonsRepository::class);
        $repo->removeAll();

        $this->assertEquals(0, $this->getPokemonNotDeletedCount());

        $this->assertEquals(26, $this->getPokemonAvailabilitiesCount('game_bundle'));
        $this->assertEquals(26, $this->getPokemonAvailabilitiesCount('game_bundle_shiny'));

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();
        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString(
            "17 pokemons' availabilities for Game Bundles calculated",
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            "16 pokemons' availabilities for Game Bundles Shiny calculated",
            $commandTester->getDisplay()
        );

        $this->assertEquals(17, $this->getPokemonAvailabilitiesCount('game_bundle'));
        $this->assertEquals(16, $this->getPokemonAvailabilitiesCount('game_bundle_shiny'));

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());
    }

    public function testPokemonAvailabilities(): void
    {
        $this->assertEquals(26, $this->getPokemonAvailabilitiesCount('game_bundle'));
        $this->assertEquals(26, $this->getPokemonAvailabilitiesCount('game_bundle_shiny'));

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();
        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString(
            "17 pokemons' availabilities for Game Bundles calculated",
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            "16 pokemons' availabilities for Game Bundles Shiny calculated",
            $commandTester->getDisplay()
        );

        $this->assertEquals(17, $this->getPokemonAvailabilitiesCount('game_bundle'));
        $this->assertEquals(16, $this->getPokemonAvailabilitiesCount('game_bundle_shiny'));

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertTrue($this->hasPokemonAvailabilities('game_bundle', 'bulbasaur'));
        $this->assertTrue($this->hasPokemonAvailabilities('game_bundle_shiny', 'bulbasaur'));
    }

    protected function getCommandName(): string
    {
        return 'app:calculate:pokemon_availabilities';
    }
}
