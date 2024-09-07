<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Service\UpdaterService\GamesUpdaterService;
use App\Api\Updater\GameBundlesUpdater;
use App\Api\Updater\GameGenerationsUpdater;
use App\Api\Updater\GamesUpdater;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class GamesUpdaterServiceTest extends TestCase
{
    public function testExecute(): void
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

        $service = new GamesUpdaterService(
            $gameGenerationsUpdater,
            $gameBundlesUpdater,
            $gamesUpdater
        );

        $service->execute();
    }
}
