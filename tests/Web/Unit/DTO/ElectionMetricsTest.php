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
        $object = new ElectionMetrics([
            'max_view' => 1,
            'max_view_count' => 2,
            'under_max_view_count' => 3,
            'elo_count' => 4,
        ]);

        $this->assertSame(1, $object->maxView);
        $this->assertSame(2, $object->maxViewCount);
        $this->assertSame(3, $object->underMaxViewCount);
        $this->assertSame(4, $object->eloCount);
    }

    public function testMissingMaxView(): void
    {
        $object = new ElectionMetrics([
            'max_view_count' => 2,
            'under_max_view_count' => 3,
            'elo_count' => 4,
        ]);

        $this->assertSame(0, $object->maxView);
        $this->assertSame(2, $object->maxViewCount);
        $this->assertSame(3, $object->underMaxViewCount);
        $this->assertSame(4, $object->eloCount);
    }

    public function testBadMaxView(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics([
            'max_view' => '1',
            'max_view_count' => 2,
            'under_max_view_count' => 3,
            'elo_count' => 4,
        ]);
    }

    public function testMissingMaxViewCount(): void
    {
        $object = new ElectionMetrics([
            'max_view' => 1,
            'under_max_view_count' => 3,
            'elo_count' => 4,
        ]);

        $this->assertSame(1, $object->maxView);
        $this->assertSame(0, $object->maxViewCount);
        $this->assertSame(3, $object->underMaxViewCount);
        $this->assertSame(4, $object->eloCount);
    }

    public function testBadMaxViewCount(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics([
            'max_view' => 1,
            'max_view_count' => '2',
            'under_max_view_count' => 3,
            'elo_count' => 4,
        ]);
    }

    public function testMissingUnderMaxViewCount(): void
    {
        $object = new ElectionMetrics([
            'max_view' => 1,
            'max_view_count' => 2,
            'elo_count' => 4,
        ]);

        $this->assertSame(1, $object->maxView);
        $this->assertSame(2, $object->maxViewCount);
        $this->assertSame(0, $object->underMaxViewCount);
        $this->assertSame(4, $object->eloCount);
    }

    public function testBadUnderMaxViewCount(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics([
            'max_view' => 1,
            'max_view_count' => 2,
            'under_max_view_count' => '3',
            'elo_count' => 4,
        ]);
    }

    public function testMissingEloCount(): void
    {
        $object = new ElectionMetrics([
            'max_view' => 1,
            'max_view_count' => 2,
            'under_max_view_count' => 3,
        ]);

        $this->assertSame(1, $object->maxView);
        $this->assertSame(2, $object->maxViewCount);
        $this->assertSame(3, $object->underMaxViewCount);
        $this->assertSame(0, $object->eloCount);
    }

    public function testBadEloCount(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionMetrics([
            'max_view' => 1,
            'max_view_count' => 2,
            'under_max_view_count' => 3,
            'elo_count' => '4',
        ]);
    }
}
