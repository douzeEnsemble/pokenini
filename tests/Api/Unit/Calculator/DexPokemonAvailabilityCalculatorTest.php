<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Calculator;

use App\Api\Calculator\DexPokemonAvailabilityCalculator;
use App\Api\DTO\GameBundlesAvailabilities;
use App\Api\DTO\GameBundlesShiniesAvailabilities;
use App\Api\DTO\GamesAvailabilities;
use App\Api\DTO\GamesShiniesAvailabilities;
use App\Api\Entity\Dex;
use App\Api\Entity\DexAvailability;
use App\Api\Entity\Pokemon;
use App\Api\Service\GameBundlesAvailabilitiesService;
use App\Api\Service\GameBundlesShiniesAvailabilitiesService;
use App\Api\Service\GamesAvailabilitiesService;
use App\Api\Service\GamesShiniesAvailabilitiesService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class DexPokemonAvailabilityCalculatorTest extends TestCase
{
    public function testCalculate(): void
    {
        $pokemon = new Pokemon();
        $pokemon->slug = 'douze';

        $gameBundlesAvailabilitiesService = $this->createMock(GameBundlesAvailabilitiesService::class);
        $gameBundlesAvailabilitiesService
            ->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn(new GameBundlesAvailabilities([
                'redgreenblueyellow' => true,
            ]))
        ;

        $gameBundlesShiniesAvailabilitiesService = $this->createMock(GameBundlesShiniesAvailabilitiesService::class);
        $gameBundlesShiniesAvailabilitiesService
            ->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn(new GameBundlesShiniesAvailabilities([
                'redgreenblueyellow' => false,
            ]))
        ;

        $gamesAvailabilitiesService = $this->createMock(GamesAvailabilitiesService::class);
        $gamesAvailabilitiesService
            ->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn(new GamesAvailabilities([
                'red' => false,
            ]))
        ;

        $gamesShiniesAvailabilitiesService = $this->createMock(GamesShiniesAvailabilitiesService::class);
        $gamesShiniesAvailabilitiesService
            ->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn(new GamesShiniesAvailabilities([
                'blue' => true,
            ]))
        ;

        $dex = new Dex();
        $dex->selectionRule = <<<'RULE'
                p.slug == 'douze' 
                and (ba?.redgreenblueyellow or bsa?.redgreenblueyellow)
                and (ga?.red or gsa?.blue)
            RULE;

        $calculate = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
        );

        $dexAvailability = $calculate->calculate($dex, $pokemon);

        $this->assertInstanceOf(DexAvailability::class, $dexAvailability);
    }

    public function testCalculateWithoutValues(): void
    {
        $pokemon = new Pokemon();

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
            ->expects($this->never())
            ->method('getFromPokemon')
        ;

        $dex = new Dex();
        $dex->selectionRule = 'true';

        $calculate = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
        );

        $dexAvailability = $calculate->calculate($dex, $pokemon);

        $this->assertInstanceOf(DexAvailability::class, $dexAvailability);
    }

    public function testCalculateWithoutGamesBundlesRegularsValues(): void
    {
        $pokemon = new Pokemon();

        $gameBundlesAvailabilitiesService = $this->createMock(GameBundlesAvailabilitiesService::class);
        $gameBundlesAvailabilitiesService
            ->expects($this->never())
            ->method('getFromPokemon')
        ;

        $gameBundlesShiniesAvailabilitiesService = $this->createMock(GameBundlesShiniesAvailabilitiesService::class);
        $gameBundlesShiniesAvailabilitiesService
            ->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn(new GameBundlesShiniesAvailabilities([
                'redgreenblueyellow' => true,
            ]))
        ;

        $gamesAvailabilitiesService = $this->createMock(GamesAvailabilitiesService::class);
        $gamesAvailabilitiesService
            ->expects($this->never())
            ->method('getFromPokemon')
        ;

        $gamesShiniesAvailabilitiesService = $this->createMock(GamesShiniesAvailabilitiesService::class);
        $gamesShiniesAvailabilitiesService
            ->expects($this->never())
            ->method('getFromPokemon')
        ;

        $dex = new Dex();
        $dex->selectionRule = 'bsa?.redgreenblueyellow';

        $calculate = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
        );

        $dexAvailability = $calculate->calculate($dex, $pokemon);

        $this->assertInstanceOf(DexAvailability::class, $dexAvailability);
    }

    public function testCalculateWithoutGamesBundlesShiniesValues(): void
    {
        $pokemon = new Pokemon();

        $gameBundlesAvailabilitiesService = $this->createMock(GameBundlesAvailabilitiesService::class);
        $gameBundlesAvailabilitiesService
            ->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn(new GameBundlesAvailabilities([
                'redgreenblueyellow' => false,
            ]))
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
            ->expects($this->never())
            ->method('getFromPokemon')
        ;

        $dex = new Dex();
        $dex->selectionRule = 'ba?.redgreenblueyellow';

        $calculate = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
        );

        $dexAvailability = $calculate->calculate($dex, $pokemon);

        $this->assertNull($dexAvailability);
    }
}
