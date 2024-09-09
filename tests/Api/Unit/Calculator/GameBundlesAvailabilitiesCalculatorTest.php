<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Calculator;

use App\Api\Calculator\AbstractCalculator;
use App\Api\Calculator\GameBundlesAvailabilitiesCalculator;
use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Repository\GameBundlesAvailabilitiesRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GameBundlesAvailabilitiesCalculator::class)]
#[CoversClass(AbstractCalculator::class)]
class GameBundlesAvailabilitiesCalculatorTest extends TestCase
{
    public function testInit(): void
    {
        $gameBundlesAvailabilitiesRepository = $this->createMock(GameBundlesAvailabilitiesRepository::class);

        $service = new GameBundlesAvailabilitiesCalculator($gameBundlesAvailabilitiesRepository);

        $service->init();

        $this->assertInstanceOf(Statistic::class, $service->getStatistic());
    }

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
