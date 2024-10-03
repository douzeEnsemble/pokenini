<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\DTO;

use App\Web\DTO\DexFilters;
use App\Web\DTO\DexFilterValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(DexFilters::class)]
class DexFiltersTest extends TestCase
{
    public function testCreateFromArrayEmpty(): void
    {
        $filters = DexFilters::createFromArray([]);

        $this->assertInstanceOf(DexFilters::class, $filters);
        $this->assertInstanceOf(DexFilterValue::class, $filters->privacy);
        $this->assertInstanceOf(DexFilterValue::class, $filters->homepaged);
        $this->assertInstanceOf(DexFilterValue::class, $filters->released);
        $this->assertInstanceOf(DexFilterValue::class, $filters->shiny);

        $this->assertNull($filters->privacy->value);
        $this->assertNull($filters->homepaged->value);
        $this->assertNull($filters->released->value);
        $this->assertNull($filters->shiny->value);
    }

    public function testCreateFromArray(): void
    {
        $filters = DexFilters::createFromArray([
            'privacy' => '1',
            'homepaged' => '1',
            'released' => '0',
            'shiny' => '0',
        ]);

        $this->assertInstanceOf(DexFilters::class, $filters);
        $this->assertInstanceOf(DexFilterValue::class, $filters->privacy);
        $this->assertInstanceOf(DexFilterValue::class, $filters->homepaged);
        $this->assertInstanceOf(DexFilterValue::class, $filters->released);
        $this->assertInstanceOf(DexFilterValue::class, $filters->shiny);

        $this->assertTrue($filters->privacy->value);
        $this->assertTrue($filters->homepaged->value);
        $this->assertFalse($filters->released->value);
        $this->assertFalse($filters->shiny->value);
    }

    public function testNormalizerTrue(): void
    {
        $filterValue = DexFilters::normalizer('1');

        $this->assertInstanceOf(DexFilterValue::class, $filterValue);
        $this->assertTrue($filterValue->value);
    }

    public function testNormalizerFalse(): void
    {
        $filterValue = DexFilters::normalizer('0');

        $this->assertInstanceOf(DexFilterValue::class, $filterValue);
        $this->assertFalse($filterValue->value);
    }

    public function testNormalizerWithEmptyValue(): void
    {
        $filterValue = DexFilters::normalizer('');

        $this->assertInstanceOf(DexFilterValue::class, $filterValue);
        $this->assertNull($filterValue->value);
    }
}
