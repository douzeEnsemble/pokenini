<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Calculator\PokemonAvailabilities;

use App\Api\Calculator\DexAvailabilitiesCalculator;
use App\Api\Calculator\DexAvailabilityCalculator;
use App\Api\Calculator\PokemonAvailabilities\GameBundlesCalculator;
use App\Api\Entity\Dex;
use App\Api\Repository\DexAvailabilitiesRepository;
use App\Api\Repository\DexRepository;
use App\Api\Repository\PokemonAvailabilitiesRepository;
use Doctrine\ORM\AbstractQuery;
use PHPUnit\Framework\TestCase;

class GameBundlesCalculatorTest extends TestCase
{
    public function testExecute(): void
    {
        $repository = $this->createMock(PokemonAvailabilitiesRepository::class);
        $repository
            ->expects($this->once())
            ->method('removeAllByCategory')
            ->with('game_bundle')
        ;
        $repository
            ->expects($this->once())
            ->method('calculateGameBundle')
            ->willReturn(12)
        ;

        $service = new GameBundlesCalculator($repository);

        $service->execute();
        $statistic = $service->getStatistic();

        $this->assertEquals(12, $statistic->count);
    }

    public function testExecuteTwice(): void
    {
        $repository = $this->createMock(PokemonAvailabilitiesRepository::class);
        $repository
            ->expects($this->exactly(2))
            ->method('removeAllByCategory')
            ->with('game_bundle')
        ;
        $repository
            ->expects($this->exactly(2))
            ->method('calculateGameBundle')
            ->willReturn(12)
        ;

        $service = new GameBundlesCalculator($repository);

        $service->execute();
        $firstCount = $service->getStatistic()->count;

        $service->execute();
        $this->assertEquals($firstCount, $service->getStatistic()->count);
    }
}
