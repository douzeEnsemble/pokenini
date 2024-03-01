<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Service\UpdaterService\DexUpdaterService;
use App\Api\Updater\DexUpdater;
use PHPUnit\Framework\TestCase;

class DexUpdaterServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $dexUpdater = $this->createMock(DexUpdater::class);
        $dexUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $dexUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('d'))
        ;

        $service = new DexUpdaterService(
            $dexUpdater,
        );

        $service->execute();
    }
}
