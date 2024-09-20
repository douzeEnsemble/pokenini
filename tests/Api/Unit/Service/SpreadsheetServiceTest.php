<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\Service\SpreadsheetService;
use Google\Client;
use Google\Service\Sheets\GridProperties;
use Google\Service\Sheets\Sheet;
use Google\Service\Sheets\SheetProperties;
use Google\Service\Sheets\Spreadsheet;
use Google\Service\Sheets\ValueRange;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @internal
 *
 * @coversNothing
 */
class SpreadsheetServiceTest extends TestCase
{
    public function testGet(): void
    {
        $logger = new NullLogger();
        $client = $this->createMock(Client::class);
        $client
            ->method('getLogger')
            ->willReturn($logger)
        ;
        $client
            ->method('shouldDefer')
            ->willReturn(false)
        ;

        $valueRange = new ValueRange();
        $client
            ->expects($this->once())
            ->method('execute')
            ->willReturn($valueRange)
        ;

        $service = new SpreadsheetService($client, 'http://moco/');

        $service->get('azertyuiop', 'A1:R12');
    }

    public function testGetSheetRowCount(): void
    {
        $service = $this->getServiceForGettingProperties();

        $this->assertEquals(
            12,
            $service->getSheetRowCount('azertyuiop', 'Toto')
        );
    }

    public function testGetSheetColumnCount(): void
    {
        $service = $this->getServiceForGettingProperties();

        $this->assertEquals(
            3,
            $service->getSheetColumnCount('azertyuiop', 'Toto')
        );
    }

    public function testGetSheetColumnCountNonExistingSheet(): void
    {
        $service = $this->getServiceForGettingProperties();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot find sheet Tutu in spreadsheet azertyuiop');

        $service->getSheetColumnCount('azertyuiop', 'Tutu');
    }

    private function getServiceForGettingProperties(): SpreadsheetService
    {
        $logger = new NullLogger();
        $client = $this->createMock(Client::class);
        $client
            ->method('getLogger')
            ->willReturn($logger)
        ;
        $client
            ->method('shouldDefer')
            ->willReturn(false)
        ;

        $titiSheetProperties = new SheetProperties();
        $titiSheetProperties->setTitle('Titi');
        $titiGridProperties = new GridProperties();
        $titiGridProperties->setRowCount(2);
        $titiGridProperties->setColumnCount(2);
        $titiSheetProperties->setGridProperties($titiGridProperties);
        $titiSheet = new Sheet();
        $titiSheet->setProperties($titiSheetProperties);

        $totoSheetProperties = new SheetProperties();
        $totoSheetProperties->setTitle('Toto');
        $totoGridProperties = new GridProperties();
        $totoGridProperties->setRowCount(12);
        $totoGridProperties->setColumnCount(3);
        $totoSheetProperties->setGridProperties($totoGridProperties);
        $totoSheet = new Sheet();
        $totoSheet->setProperties($totoSheetProperties);

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setSheets([
            $titiSheet,
            $totoSheet,
        ]);

        $client
            ->expects($this->once())
            ->method('execute')
            ->willReturn($spreadsheet)
        ;

        return new SpreadsheetService($client, 'http://moco/');
    }
}
