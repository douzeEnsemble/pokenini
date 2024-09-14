<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Service\UpdaterService\GamesShiniesAvailabilitiesUpdaterService;
use App\Api\Updater\GamesShiniesAvailabilitiesUpdater;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GamesShiniesAvailabilitiesUpdaterService::class)]
class GamesShiniesAvailabilitiesUpdaterServiceTest extends TestCase
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
        $this->assertIsArray($report->detail);
        $this->assertInstanceOf(Statistic::class, $report->detail[0]);
    }

    private function getService(): GamesShiniesAvailabilitiesUpdaterService
    {
        $gamesShiniesAvailabilitiesUpdater = $this->createMock(GamesShiniesAvailabilitiesUpdater::class);
        $gamesShiniesAvailabilitiesUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $gamesShiniesAvailabilitiesUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('ga'))
        ;

        return new GamesShiniesAvailabilitiesUpdaterService(
            $gamesShiniesAvailabilitiesUpdater
        );
    }
}
