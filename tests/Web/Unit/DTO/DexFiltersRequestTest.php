<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\DTO;

use App\Web\DTO\DexFilters;
use App\Web\DTO\DexFiltersRequest;
use App\Web\DTO\DexFilterValue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
#[CoversClass(DexFiltersRequest::class)]
class DexFiltersRequestTest extends TestCase
{
    public function testDexFiltersFromRequestEmpty(): void
    {
        $request = new Request([]);

        $filters = DexFiltersRequest::DexFiltersFromRequest($request);

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

    public function testDexFiltersFromRequest(): void
    {
        $request = new Request([
            'p' => '1',
            'h' => '0',
            'r' => '1',
            's' => '0',
        ]);

        $filters = DexFiltersRequest::DexFiltersFromRequest($request);

        $this->assertInstanceOf(DexFilters::class, $filters);
        $this->assertInstanceOf(DexFilterValue::class, $filters->privacy);
        $this->assertInstanceOf(DexFilterValue::class, $filters->homepaged);
        $this->assertInstanceOf(DexFilterValue::class, $filters->released);
        $this->assertInstanceOf(DexFilterValue::class, $filters->shiny);

        $this->assertTrue($filters->privacy->value);
        $this->assertFalse($filters->homepaged->value);
        $this->assertTrue($filters->released->value);
        $this->assertFalse($filters->shiny->value);
    }
}
