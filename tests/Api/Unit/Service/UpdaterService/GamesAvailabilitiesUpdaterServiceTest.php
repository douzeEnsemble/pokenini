<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Service\UpdaterService\GamesAvailabilitiesUpdaterService;
use App\Api\Updater\GamesAvailabilitiesUpdater;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class GamesAvailabilitiesUpdaterServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $gamesAvailabilitiesUpdater = $this->createMock(GamesAvailabilitiesUpdater::class);
        $gamesAvailabilitiesUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $gamesAvailabilitiesUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('ga'))
        ;

        $service = new GamesAvailabilitiesUpdaterService(
            $gamesAvailabilitiesUpdater
        );

        $service->execute();
    }
}
