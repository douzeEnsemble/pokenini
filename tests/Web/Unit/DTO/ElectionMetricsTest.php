<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\DTO;

use App\Web\DTO\ElectionMetrics;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * @internal
 */
#[CoversClass(ElectionMetrics::class)]
class ElectionMetricsTest extends TestCase
{
    public function testOk(): void
    {
        $object = new ElectionMetrics([
            'avg_elo' => 12.1,
            'stddev_elo' => 24.46568468,
            'count_elo' => 4,
        ]);

        $this->assertSame(12.1, $object->avg);
        $this->assertSame(24.46568468, $object->stddev);
        $this->assertSame(4, $object->count);
    }

    public function testIntAvg(): void
    {
        $object = new ElectionMetrics([
            'avg_elo' => 12,
            'stddev_elo' => 24.46568468,
            'count_elo' => 4,
        ]);

        $this->assertSame(12.0, $object->avg);
        $this->assertSame(24.46568468, $object->stddev);
        $this->assertSame(4, $object->count);
    }

    public function testIntStddev(): void
    {
        $object = new ElectionMetrics([
            'avg_elo' => 12.1,
            'stddev_elo' => 0,
            'count_elo' => 4,
        ]);

        $this->assertSame(12.1, $object->avg);
        $this->assertSame(0.0, $object->stddev);
        $this->assertSame(4, $object->count);
    }

    public function testMissingAvg(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics([
            'stddev_elo' => 24.46568468,
            'count_elo' => 4,
        ]);
    }

    public function testMissingStddev(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics([
            'avg_elo' => 12,
            'count_elo' => 4,
        ]);
    }

    public function testMissingCount(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics([
            'avg_elo' => 12,
            'stddev_elo' => 24.46568468,
        ]);
    }

    public function testBadAvg(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics([
            'avg_elo' => '12',
            'stddev_elo' => 24.46568468,
            'count_elo' => 4,
        ]);
    }

    public function testBadStddev(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics([
            'avg_elo' => 12,
            'stddev_elo' => '24.46568468',
            'count_elo' => 4,
        ]);
    }

    public function testBadCount(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics([
            'avg_elo' => 12,
            'stddev_elo' => 24.46568468,
            'count_elo' => '4',
        ]);
    }
}
