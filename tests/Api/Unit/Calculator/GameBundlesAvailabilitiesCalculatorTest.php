<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Calculator;

use App\Api\Calculator\GameBundlesAvailabilitiesCalculator;
use App\Api\Repository\GameBundlesAvailabilitiesRepository;
use PHPUnit\Framework\TestCase;

class GameBundlesAvailabilitiesCalculatorTest extends TestCase
{
    public function testExecute(): void
    {
        $gameBundlesAvailabilitiesRepository = $this->createMock(GameBundlesAvailabilitiesRepository::class);
        $gameBundlesAvailabilitiesRepository
            ->expects($this->once())
            ->method('removeAll')
        ;
        $gameBundlesAvailabilitiesRepository
            ->expects($this->once())
            ->method('calculate')
            ->willReturn(12)
        ;

        $service = new GameBundlesAvailabilitiesCalculator($gameBundlesAvailabilitiesRepository);
        $service->execute();
        $statistic = $service->getStatistic();

        $this->assertEquals(12, $statistic->count);
    }

    public function testExecuteTwice(): void
    {
        $gameBundlesAvailabilitiesRepository = $this->createMock(GameBundlesAvailabilitiesRepository::class);
        $gameBundlesAvailabilitiesRepository
            ->expects($this->exactly(2))
            ->method('removeAll')
        ;
        $gameBundlesAvailabilitiesRepository
            ->expects($this->exactly(2))
            ->method('calculate')
            ->willReturn(12)
        ;

        $service = new GameBundlesAvailabilitiesCalculator($gameBundlesAvailabilitiesRepository);

        $service->execute();

        $firstCount = $service->getStatistic()->count;

        $service->execute();

        $this->assertEquals($firstCount, $service->getStatistic()->count);
    }
}
