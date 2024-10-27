<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Service\UpdaterService\GamesUpdaterService;
use App\Api\Updater\GameBundlesUpdater;
use App\Api\Updater\GameGenerationsUpdater;
use App\Api\Updater\GamesUpdater;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GamesUpdaterService::class)]
class GamesUpdaterServiceTest extends TestCase
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

    private function getService(): GamesUpdaterService
    {
        $gameGenerationsUpdater = $this->createMock(GameGenerationsUpdater::class);
        $gameGenerationsUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $gameGenerationsUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('gg'))
        ;

        $gameBundlesUpdater = $this->createMock(GameBundlesUpdater::class);
        $gameBundlesUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $gameBundlesUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('gb'))
        ;

        $gamesUpdater = $this->createMock(GamesUpdater::class);
        $gamesUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $gamesUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('g'))
        ;

        return new GamesUpdaterService(
            $gameGenerationsUpdater,
            $gameBundlesUpdater,
            $gamesUpdater
        );
    }
}
