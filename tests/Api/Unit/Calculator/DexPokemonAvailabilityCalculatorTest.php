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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(DexPokemonAvailabilityCalculator::class)]
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

        $calculator = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
        );

        $dexAvailability = $calculator->calculate($dex, $pokemon);

        $this->assertNotNull($dexAvailability);
    }

    public function testCalculateNotAvailable(): void
    {
        $pokemon = new Pokemon();
        $pokemon->slug = 'douze';

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
        $dex->selectionRule = <<<'RULE'
                p.slug == 'pikachu'
            RULE;

        $calculator = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
        );

        $dexAvailability = $calculator->calculate($dex, $pokemon);

        $this->assertNull($dexAvailability);
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

        $calculator = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
        );

        $dexAvailability = $calculator->calculate($dex, $pokemon);

        $this->assertInstanceOf(DexAvailability::class, $dexAvailability);
    }

    #[DataProvider('providerCalculateIncludingPokemonValues')]
    public function testCalculateIncludingPokemonValues(string $rule): void
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
        );

        $dexAvailability = $calculator->calculate($dex, $pokemon);

        $this->assertNotNull($dexAvailability);
    }

    /**
     * @return string[][]
     */
    public static function providerCalculateIncludingPokemonValues(): array
    {
        return [
            'p' => [
                'p.slug == "pikachu"',
            ],
            'p?' => [
                'p?.slug == "pikachu"',
            ],
            'p_p?' => [
                'p.slug == "pikachu" or p?.slug == "raichu"',
            ],
        ];
    }

    #[DataProvider('providerCalculateIncludingGameBundlesValues')]
    public function testCalculateIncludingGameBundlesValues(string $rule): void
    {
        $pokemon = new Pokemon();
        $pokemon->slug = 'pikachu';

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
        $dex->selectionRule = $rule;

        $calculator = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
        );

        $dexAvailability = $calculator->calculate($dex, $pokemon);

        $this->assertNotNull($dexAvailability);
    }

    /**
     * @return string[][]
     */
    public static function providerCalculateIncludingGameBundlesValues(): array
    {
        return [
            'ba' => [
                'ba.redgreenblueyellow',
            ],
            'ba?' => [
                'ba?.redgreenblueyellow',
            ],
            'ba_ba?' => [
                'ba.redgreenblueyellow or ba?.redgreenblueyellow',
            ],
        ];
    }

    #[DataProvider('providerCalculateIncludingGameBundlesShiniesValues')]
    public function testCalculateIncludingGameBundlesShiniesValues(string $rule): void
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
        $dex->selectionRule = $rule;

        $calculator = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
        );

        $dexAvailability = $calculator->calculate($dex, $pokemon);

        $this->assertNotNull($dexAvailability);
    }

    /**
     * @return string[][]
     */
    public static function providerCalculateIncludingGameBundlesShiniesValues(): array
    {
        return [
            'bsa' => [
                'bsa.redgreenblueyellow',
            ],
            'bsa?' => [
                'bsa?.redgreenblueyellow',
            ],
            'bsa_bsa?' => [
                'bsa.redgreenblueyellow or bsa?.redgreenblueyellow',
            ],
        ];
    }

    #[DataProvider('providerCalculateIncludingGamesValues')]
    public function testCalculateIncludingGamesValues(string $rule): void
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
            ->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn(new GamesAvailabilities([
                'yellow' => true,
            ]))
        ;

        $gamesShiniesAvailabilitiesService = $this->createMock(GamesShiniesAvailabilitiesService::class);
        $gamesShiniesAvailabilitiesService
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
        );

        $dexAvailability = $calculator->calculate($dex, $pokemon);

        $this->assertNotNull($dexAvailability);
    }

    /**
     * @return string[][]
     */
    public static function providerCalculateIncludingGamesValues(): array
    {
        return [
            'ga' => [
                'ga.yellow',
            ],
            'ga?' => [
                'ga?.yellow',
            ],
            'ga_ga?' => [
                'ga.yellow or ga?.yellow',
            ],
        ];
    }

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

        $dex = new Dex();
        $dex->selectionRule = $rule;

        $calculator = new DexPokemonAvailabilityCalculator(
            $gameBundlesAvailabilitiesService,
            $gameBundlesShiniesAvailabilitiesService,
            $gamesAvailabilitiesService,
            $gamesShiniesAvailabilitiesService,
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
