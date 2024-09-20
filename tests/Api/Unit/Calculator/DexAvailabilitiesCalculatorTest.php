<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Calculator;

use App\Api\Calculator\DexAvailabilitiesCalculator;
use App\Api\Calculator\DexAvailabilityCalculator;
use App\Api\Entity\Dex;
use App\Api\Repository\DexAvailabilitiesRepository;
use App\Api\Repository\DexRepository;
use Doctrine\ORM\AbstractQuery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(DexAvailabilitiesCalculator::class)]
class DexAvailabilitiesCalculatorTest extends TestCase
{
    public function testExecute(): void
    {
        $dexAvailabilitiesRepository = $this->createMock(DexAvailabilitiesRepository::class);
        $dexAvailabilitiesRepository
            ->expects($this->once())
            ->method('removeAll')
        ;

        $dexQuery = $this->createMock(AbstractQuery::class);
        $dexQuery
            ->expects($this->once())
            ->method('toIterable')
            ->willReturn([
                new Dex(),
                new Dex(),
            ])
        ;
        $dexRepository = $this->createMock(DexRepository::class);
        $dexRepository
            ->expects($this->once())
            ->method('getQueryAll')
            ->willReturn($dexQuery)
        ;

        $dexAvailabilityCalculator = $this->createMock(DexAvailabilityCalculator::class);
        $dexAvailabilityCalculator
            ->expects($this->exactly(2))
            ->method('calculate')
            ->willReturn(3)
        ;

        $service = new DexAvailabilitiesCalculator(
            $dexAvailabilitiesRepository,
            $dexRepository,
            $dexAvailabilityCalculator,
        );

        $service->execute();
        $statistic = $service->getStatistic();

        $this->assertEquals(6, $statistic->count);
    }

    public function testExecuteTwice(): void
    {
        $dexAvailabilitiesRepository = $this->createMock(DexAvailabilitiesRepository::class);
        $dexAvailabilitiesRepository
            ->expects($this->exactly(2))
            ->method('removeAll')
        ;

        $dexQuery = $this->createMock(AbstractQuery::class);
        $dexQuery
            ->expects($this->exactly(2))
            ->method('toIterable')
            ->willReturn([
                new Dex(),
                new Dex(),
            ])
        ;
        $dexRepository = $this->createMock(DexRepository::class);
        $dexRepository
            ->expects($this->exactly(2))
            ->method('getQueryAll')
            ->willReturn($dexQuery)
        ;

        $dexAvailabilityCalculator = $this->createMock(DexAvailabilityCalculator::class);
        $dexAvailabilityCalculator
            ->expects($this->exactly(4))
            ->method('calculate')
            ->willReturn(3)
        ;

        $service = new DexAvailabilitiesCalculator(
            $dexAvailabilitiesRepository,
            $dexRepository,
            $dexAvailabilityCalculator,
        );

        $service->execute();
        $firstCount = $service->getStatistic()->count;

        $service->execute();
        $this->assertEquals($firstCount, $service->getStatistic()->count);
    }
}
