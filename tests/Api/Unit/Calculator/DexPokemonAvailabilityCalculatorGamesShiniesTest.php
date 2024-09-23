<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Calculator;

use App\Api\Calculator\DexPokemonAvailabilityCalculator;
use App\Api\DTO\GamesShiniesAvailabilities;
use App\Api\Entity\Dex;
use App\Api\Entity\Pokemon;
use App\Api\Service\CollectionsAvailabilitiesService;
use App\Api\Service\GameBundlesAvailabilitiesService;
use App\Api\Service\GameBundlesShiniesAvailabilitiesService;
use App\Api\Service\GamesAvailabilitiesService;
use App\Api\Service\GamesShiniesAvailabilitiesService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(DexPokemonAvailabilityCalculator::class)]
class DexPokemonAvailabilityCalculatorGamesShiniesTest extends TestCase
{
    #[DataProvider('providerCalculateIncludingGamesShiniesValues')]
    public function testCalculateIncludingGamesShiniesValues(string $rule): void
    {
        $pokemon = new Pokemon();
        $pokemon->slug = 'pikachu';

        $gameBundlesAvailabilitiesService = $this->createMock(GameBundlesAvailabilitiesService::class);
        $gameBundlesAvailabilitiesService
            ->expects($this->never())
            ->method('getFromPokemon')
        ;

        $gameBundlesShiniesAvailabilitiesService = $this->createMock(GameBundlesShiniesAvailabilitiesService::class);
        $gameBundlesShiniesAvailabilitiesService
            ->expects($this->never())
            ->method('getFromPokemon')
        ;

        $gamesAvailabilitiesService = $this->createMock(GamesAvailabilitiesService::class);
        $gamesAvailabilitiesService
            ->expects($this->never())
            ->method('getFromPokemon')
        ;

        $gamesShiniesAvailabilitiesService = $this->createMock(GamesShiniesAvailabilitiesService::class);
        $gamesShiniesAvailabilitiesService
            ->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn(new GamesShiniesAvailabilities([
                'yellow' => true,
            ]))
        ;

        $collectionAvailabilitiesService = $this->createMock(CollectionsAvailabilitiesService::class);
        $collectionAvailabilitiesService
            ->expects($this->never())
            ->method('getFromPokemon')
        ;

        $dex = new Dex();
        $dex->selectionRule = $rule;

        $calculator = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
            $collectionAvailabilitiesService,
        );

        $dexAvailability = $calculator->calculate($dex, $pokemon);

        $this->assertNotNull($dexAvailability);
    }

    /**
     * @return string[][]
     */
    public static function providerCalculateIncludingGamesShiniesValues(): array
    {
        return [
            'gsa' => [
                'gsa.yellow',
            ],
            'gsa?' => [
                'gsa?.yellow',
            ],
            'gsa_gsa?' => [
                'gsa.yellow or gsa?.yellow',
            ],
        ];
    }
}
