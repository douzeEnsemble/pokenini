<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Service\UpdaterService\CollectionsAvailabilitiesUpdaterService;
use App\Api\Updater\CollectionsAvailabilitiesUpdater;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(CollectionsAvailabilitiesUpdaterService::class)]
class CollectionsAvailabilitiesUpdaterServiceTest extends TestCase
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

    private function getService(): CollectionsAvailabilitiesUpdaterService
    {
        $collectionsAvailabilitiesUpdater = $this->createMock(CollectionsAvailabilitiesUpdater::class);
        $collectionsAvailabilitiesUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $collectionsAvailabilitiesUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('ca'))
        ;

        return new CollectionsAvailabilitiesUpdaterService(
            $collectionsAvailabilitiesUpdater
        );
    }
}
