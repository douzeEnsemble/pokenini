<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Calculator;

use App\Api\Calculator\GameBundlesShiniesAvailabilitiesCalculator;
use App\Api\Repository\GameBundlesShiniesAvailabilitiesRepository;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class GameBundlesShiniesAvailabilitiesCalculatorTest extends TestCase
{
    public function testExecute(): void
    {
        $gameBundlesShiniesAvailabilitiesRepository =
            $this->createMock(GameBundlesShiniesAvailabilitiesRepository::class);
        $gameBundlesShiniesAvailabilitiesRepository
            ->expects($this->once())
            ->method('removeAll')
        ;
        $gameBundlesShiniesAvailabilitiesRepository
            ->expects($this->once())
            ->method('calculate')
            ->willReturn(12)
        ;

        $service = new GameBundlesShiniesAvailabilitiesCalculator(
            $gameBundlesShiniesAvailabilitiesRepository
        );
        $service->execute();
        $statistic = $service->getStatistic();

        $this->assertEquals(12, $statistic->count);
    }

    public function testExecuteTwice(): void
    {
        $gameBundlesShiniesAvailabilitiesRepository = $this->createMock(
            GameBundlesShiniesAvailabilitiesRepository::class
        );
        $gameBundlesShiniesAvailabilitiesRepository
            ->expects($this->exactly(2))
            ->method('removeAll')
        ;
        $gameBundlesShiniesAvailabilitiesRepository
            ->expects($this->exactly(2))
            ->method('calculate')
            ->willReturn(12)
        ;

        $service = new GameBundlesShiniesAvailabilitiesCalculator($gameBundlesShiniesAvailabilitiesRepository);

        $service->execute();

        $firstCount = $service->getStatistic()->count;

        $service->execute();

        $this->assertEquals($firstCount, $service->getStatistic()->count);
    }
}
