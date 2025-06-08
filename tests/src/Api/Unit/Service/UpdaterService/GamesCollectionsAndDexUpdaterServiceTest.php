<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\Service\UpdaterService\CollectionsUpdaterService;
use App\Api\Service\UpdaterService\DexUpdaterService;
use App\Api\Service\UpdaterService\GamesCollectionsAndDexUpdaterService;
use App\Api\Service\UpdaterService\GamesUpdaterService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GamesCollectionsAndDexUpdaterService::class)]
class GamesCollectionsAndDexUpdaterServiceTest extends TestCase
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
        $this->assertEmpty($report->detail);
    }

    private function getService(): GamesCollectionsAndDexUpdaterService
    {
        $gamesUpdaterService = $this->createMock(GamesUpdaterService::class);
        $gamesUpdaterService
            ->expects($this->once())
            ->method('execute')
        ;
        $gamesUpdaterService
            ->expects($this->once())
            ->method('getReport')
            ->willReturn(new Report([]))
        ;

        $dexUpdaterService = $this->createMock(DexUpdaterService::class);
        $dexUpdaterService
            ->expects($this->once())
            ->method('execute')
        ;
        $dexUpdaterService
            ->expects($this->once())
            ->method('getReport')
            ->willReturn(new Report([]))
        ;

        $collectionsUpdaterService = $this->createMock(CollectionsUpdaterService::class);
        $collectionsUpdaterService
            ->expects($this->once())
            ->method('execute')
        ;
        $collectionsUpdaterService
            ->expects($this->once())
            ->method('getReport')
            ->willReturn(new Report([]))
        ;

        return new GamesCollectionsAndDexUpdaterService(
            $gamesUpdaterService,
            $dexUpdaterService,
            $collectionsUpdaterService,
        );
    }
}
