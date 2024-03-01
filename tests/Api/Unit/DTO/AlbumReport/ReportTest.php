<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO\AlbumReport;

use App\Api\DTO\AlbumReport\Report;
use App\Api\DTO\AlbumReport\Statistic;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    public function testConstructor(): void
    {
        $report = new Report(
            12,
            1,
            5,
            [
                new Statistic('no', 'No', 'Non', 1),
                new Statistic('yes', 'Yes', 'Yes', 1),
            ]
        );

        $this->assertEquals(12, $report->total);
        $this->assertEquals(1, $report->totalCaught);
        $this->assertEquals(5, $report->totalUncaught);
        $this->assertCount(2, $report->detail);
    }
}
