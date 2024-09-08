<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Service\UpdaterService\FormsUpdaterService;
use App\Api\Service\UpdaterService\LabelsUpdaterService;
use App\Api\Updater\CatchStatesUpdater;
use App\Api\Updater\RegionsUpdater;
use App\Api\Updater\TypesUpdater;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LabelsUpdaterService::class)]
class LabelsUpdaterServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $formsUpdaterService = $this->createMock(FormsUpdaterService::class);
        $formsUpdaterService
            ->expects($this->once())
            ->method('execute')
        ;
        $formsUpdaterService
            ->expects($this->once())
            ->method('getReport')
            ->willReturn(new Report([]))
        ;

        $catchStatesUpdater = $this->createMock(CatchStatesUpdater::class);
        $catchStatesUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $catchStatesUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('cs'))
        ;

        $regionsUpdater = $this->createMock(RegionsUpdater::class);
        $regionsUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $regionsUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('r'))
        ;

        $typesUpdater = $this->createMock(TypesUpdater::class);
        $typesUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $typesUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('t'))
        ;

        $service = new LabelsUpdaterService(
            $catchStatesUpdater,
            $formsUpdaterService,
            $regionsUpdater,
            $typesUpdater,
        );

        $service->execute();
    }
}
