<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Service\UpdaterService\RegionalDexNumbersUpdaterService;
use App\Api\Updater\RegionalDexNumbersUpdater;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class RegionalDexNumbersUpdaterServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $regionalDexNumbersUpdater = $this->createMock(RegionalDexNumbersUpdater::class);
        $regionalDexNumbersUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $regionalDexNumbersUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('rdn'))
        ;

        $service = new RegionalDexNumbersUpdaterService(
            $regionalDexNumbersUpdater
        );

        $service->execute();
    }
}
