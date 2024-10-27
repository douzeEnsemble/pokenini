<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Service\UpdaterService\FormsUpdaterService;
use App\Api\Updater\Forms\CategoryFormsUpdater;
use App\Api\Updater\Forms\RegionalFormsUpdater;
use App\Api\Updater\Forms\SpecialFormsUpdater;
use App\Api\Updater\Forms\VariantFormsUpdater;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(FormsUpdaterService::class)]
class FormsUpdaterServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $service = $this->getService();

        $service->execute();
    }

    public function testGetReport(): void
    {
        $service = $this->getService();

        $service->execute();
        $report = $service->getReport();

        $this->assertInstanceOf(Report::class, $report);
        $this->assertInstanceOf(Statistic::class, $report->detail[0]);
    }

    private function getService(): FormsUpdaterService
    {
        $categoryFormUpdater = $this->createMock(CategoryFormsUpdater::class);
        $categoryFormUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $categoryFormUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('cat'))
        ;

        $regionalFormUpdater = $this->createMock(RegionalFormsUpdater::class);
        $regionalFormUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $regionalFormUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('region'))
        ;

        $specialFormUpdater = $this->createMock(SpecialFormsUpdater::class);
        $specialFormUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $specialFormUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('spec'))
        ;

        $variantFormUpdater = $this->createMock(VariantFormsUpdater::class);
        $variantFormUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $variantFormUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('var'))
        ;

        return new FormsUpdaterService(
            $categoryFormUpdater,
            $regionalFormUpdater,
            $specialFormUpdater,
            $variantFormUpdater
        );
    }
}
