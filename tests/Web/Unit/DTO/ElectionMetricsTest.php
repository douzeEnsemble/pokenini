<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\DTO;

use App\Web\DTO\ElectionMetrics;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @internal
 */
#[CoversClass(ElectionMetrics::class)]
class ElectionMetricsTest extends TestCase
{
    public function testOk(): void
    {
        $object = new ElectionMetrics(
            [
                'view_count_sum' => 82,
                'win_count_sum' => 54,
                'dex_total_count' => 50,
            ],
            12,
        );

        $this->assertSame(82, $object->viewCountSum);
        $this->assertSame(54, $object->winCountSum);
        $this->assertSame(50, $object->dexTotalCount);

        $this->assertSame(7, $object->roundCount);
        $this->assertSame(7.71, $object->winnerAverage);
        $this->assertSame(5, $object->totalRoundCount);
    }

    public function testSumZero(): void
    {
        $object = new ElectionMetrics(
            [
                'view_count_sum' => 0,
                'win_count_sum' => 0,
                'dex_total_count' => 50,
            ],
            12,
        );

        $this->assertSame(0, $object->viewCountSum);
        $this->assertSame(0, $object->winCountSum);
        $this->assertSame(50, $object->dexTotalCount);

        $this->assertSame(0, $object->roundCount);
        $this->assertSame(4.0, $object->winnerAverage);
        $this->assertSame(5, $object->totalRoundCount);
    }

    public function testEdge(): void
    {
        $object = new ElectionMetrics(
            [
                'view_count_sum' => 1,
                'win_count_sum' => 1,
                'dex_total_count' => 50,
            ],
            120,
        );

        $this->assertSame(1, $object->viewCountSum);
        $this->assertSame(1, $object->winCountSum);
        $this->assertSame(50, $object->dexTotalCount);

        $this->assertSame(0, $object->roundCount);
        $this->assertSame(4.0, $object->winnerAverage);
        $this->assertSame(0, $object->totalRoundCount);
    }

    public function testFloor(): void
    {
        $object = new ElectionMetrics(
            [
                'view_count_sum' => 120,
                'win_count_sum' => 28,
                'dex_total_count' => 17,
            ],
            12,
        );

        $this->assertSame(120, $object->viewCountSum);
        $this->assertSame(28, $object->winCountSum);
        $this->assertSame(17, $object->dexTotalCount);

        $this->assertSame(10, $object->roundCount);
        $this->assertSame(2.8, $object->winnerAverage);
        $this->assertSame(2, $object->totalRoundCount);
    }

    public function testMissingViewCountSum(): void
    {
        $object = new ElectionMetrics(
            [
                'win_count_sum' => 2,
                'dex_total_count' => 50,
            ],
            12,
        );

        $this->assertSame(0, $object->viewCountSum);
        $this->assertSame(2, $object->winCountSum);
        $this->assertSame(50, $object->dexTotalCount);

        $this->assertSame(0, $object->roundCount);
        $this->assertSame(4.0, $object->winnerAverage);
        $this->assertSame(5, $object->totalRoundCount);
    }

    public function testBadViewCountSum(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics(
            [
                'view_count_sum' => '1',
                'win_count_sum' => 2,
                'dex_total_count' => 50,
            ],
            12,
        );
    }

    public function testMissingWinCountSum(): void
    {
        $object = new ElectionMetrics(
            [
                'view_count_sum' => 1,
                'dex_total_count' => 50,
            ],
            12,
        );

        $this->assertSame(1, $object->viewCountSum);
        $this->assertSame(0, $object->winCountSum);
        $this->assertSame(50, $object->dexTotalCount);

        $this->assertSame(0, $object->roundCount);
        $this->assertSame(4.0, $object->winnerAverage);
        $this->assertSame(5, $object->totalRoundCount);
    }

    public function testBadWinCountSum(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics(
            [
                'view_count_sum' => 1,
                'win_count_sum' => '2',
                'dex_total_count' => 50,
            ],
            12,
        );
    }

    public function testMissingdexTotalCount(): void
    {
        $object = new ElectionMetrics(
            [
                'view_count_sum' => 1,
                'win_count_sum' => 2,
            ],
            12,
        );

        $this->assertSame(1, $object->viewCountSum);
        $this->assertSame(2, $object->winCountSum);
        $this->assertSame(0, $object->dexTotalCount);

        $this->assertSame(0, $object->roundCount);
        $this->assertSame(4.0, $object->winnerAverage);
        $this->assertSame(0, $object->totalRoundCount);
    }

    public function testBadDexTotalCount(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics(
            [
                'view_count_sum' => 1,
                'win_count_sum' => 2,
                'dex_total_count' => '50',
            ],
            12,
        );
    }
}
