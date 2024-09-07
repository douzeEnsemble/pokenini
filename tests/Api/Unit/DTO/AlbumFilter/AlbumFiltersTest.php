<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO\AlbumFilter;

use App\Api\DTO\AlbumFilter\AlbumFilters;
use App\Api\DTO\AlbumFilter\AlbumFilterValues;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class AlbumFiltersTest extends TestCase
{
    public function testCreateFromArrayEmpty(): void
    {
        $filters = AlbumFilters::createFromArray([]);

        $this->assertInstanceOf(AlbumFilters::class, $filters);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->primaryTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->secondaryTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->anyTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->categoryForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->regionalForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->specialForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->variantForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->originalGameBundles);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->gameBundleAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->gameBundleShinyAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->families);

        $this->assertEmpty($filters->primaryTypes->values);
        $this->assertEmpty($filters->secondaryTypes->values);
        $this->assertEmpty($filters->anyTypes->values);
        $this->assertEmpty($filters->categoryForms->values);
        $this->assertEmpty($filters->regionalForms->values);
        $this->assertEmpty($filters->specialForms->values);
        $this->assertEmpty($filters->variantForms->values);
        $this->assertEmpty($filters->originalGameBundles->values);
        $this->assertEmpty($filters->gameBundleAvailabilities->values);
        $this->assertEmpty($filters->gameBundleShinyAvailabilities->values);
        $this->assertEmpty($filters->families->values);
    }

    public function testCreateFromArray(): void
    {
        $filters = AlbumFilters::createFromArray([
            'primaryTypes' => ['fire', 'water'],
            'secondaryTypes' => ['water', 'fire'],
            'anyTypes' => ['normal'],
            'categoryForms' => ['starter', 'finisher'],
            'regionalForms' => ['provence', 'sud', 'mer'],
            'specialForms' => ['banana', 'orange'],
            'variantForms' => ['gender'],
            'originalGameBundles' => ['redgreenblueyellow'],
            'gameBundleAvailabilities' => ['sunmoon'],
            'gameBundleShinyAvailabilities' => ['ultrasunutramoon'],
            'families' => ['pichu', 'eevee'],
        ]);

        $this->assertInstanceOf(AlbumFilters::class, $filters);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->primaryTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->secondaryTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->anyTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->categoryForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->regionalForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->specialForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->variantForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->originalGameBundles);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->gameBundleAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->gameBundleShinyAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->families);

        $this->assertCount(2, $filters->primaryTypes->values);
        $this->assertCount(2, $filters->secondaryTypes->values);
        $this->assertCount(1, $filters->anyTypes->values);
        $this->assertCount(2, $filters->categoryForms->values);
        $this->assertCount(3, $filters->regionalForms->values);
        $this->assertCount(2, $filters->specialForms->values);
        $this->assertCount(1, $filters->variantForms->values);
        $this->assertCount(1, $filters->originalGameBundles->values);
        $this->assertCount(1, $filters->gameBundleAvailabilities->values);
        $this->assertCount(1, $filters->gameBundleShinyAvailabilities->values);
        $this->assertCount(2, $filters->families->values);
    }

    public function testNormalizer(): void
    {
        $filterValues = AlbumFilters::normalizer(['a', 'b', 'c']);

        $this->assertInstanceOf(AlbumFilterValues::class, $filterValues);
        $this->assertCount(3, $filterValues->values);
        $this->assertFalse($filterValues->hasNull());
    }

    public function testNormalizerWithNullValue(): void
    {
        $filterValues = AlbumFilters::normalizer(['a', 'null', 'c']);

        $this->assertInstanceOf(AlbumFilterValues::class, $filterValues);
        $this->assertCount(3, $filterValues->values);
        $this->assertTrue($filterValues->hasNull());
    }

    public function testNormalizerWithEmptyValue(): void
    {
        $filterValues = AlbumFilters::normalizer(['a', '', 'c']);

        $this->assertInstanceOf(AlbumFilterValues::class, $filterValues);
        $this->assertCount(2, $filterValues->values);
        $this->assertFalse($filterValues->hasNull());
    }
}
