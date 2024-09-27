<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO\AlbumFilter;

use App\Api\DTO\AlbumFilter\AlbumFilters;
use App\Api\DTO\AlbumFilter\AlbumFilterValues;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(AlbumFilters::class)]
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
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->catchStates);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->originalGameBundles);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->gameBundleAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->gameBundleShinyAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->families);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->collectionAvailabilities);

        $this->assertEmpty($filters->primaryTypes->values);
        $this->assertEmpty($filters->secondaryTypes->values);
        $this->assertEmpty($filters->anyTypes->values);
        $this->assertEmpty($filters->categoryForms->values);
        $this->assertEmpty($filters->regionalForms->values);
        $this->assertEmpty($filters->specialForms->values);
        $this->assertEmpty($filters->variantForms->values);
        $this->assertEmpty($filters->catchStates->values);
        $this->assertEmpty($filters->originalGameBundles->values);
        $this->assertEmpty($filters->gameBundleAvailabilities->values);
        $this->assertEmpty($filters->gameBundleShinyAvailabilities->values);
        $this->assertEmpty($filters->families->values);
        $this->assertEmpty($filters->collectionAvailabilities->values);

        $this->assertEmpty($filters->primaryTypes->negativeValues);
        $this->assertEmpty($filters->secondaryTypes->negativeValues);
        $this->assertEmpty($filters->anyTypes->negativeValues);
        $this->assertEmpty($filters->categoryForms->negativeValues);
        $this->assertEmpty($filters->regionalForms->negativeValues);
        $this->assertEmpty($filters->specialForms->negativeValues);
        $this->assertEmpty($filters->variantForms->negativeValues);
        $this->assertEmpty($filters->catchStates->negativeValues);
        $this->assertEmpty($filters->originalGameBundles->negativeValues);
        $this->assertEmpty($filters->gameBundleAvailabilities->negativeValues);
        $this->assertEmpty($filters->gameBundleShinyAvailabilities->negativeValues);
        $this->assertEmpty($filters->families->negativeValues);
        $this->assertEmpty($filters->collectionAvailabilities->negativeValues);
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
            'catchStates' => ['maybe'],
            'originalGameBundles' => ['redgreenblueyellow'],
            'gameBundleAvailabilities' => ['sunmoon'],
            'gameBundleShinyAvailabilities' => ['ultrasunutramoon'],
            'families' => ['pichu', 'eevee'],
            'collectionAvailabilities' => ['swshdens'],
        ]);

        $this->assertInstanceOf(AlbumFilters::class, $filters);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->primaryTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->secondaryTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->anyTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->categoryForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->regionalForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->specialForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->variantForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->catchStates);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->originalGameBundles);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->gameBundleAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->gameBundleShinyAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->families);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->collectionAvailabilities);

        $this->assertCount(2, $filters->primaryTypes->values);
        $this->assertCount(2, $filters->secondaryTypes->values);
        $this->assertCount(1, $filters->anyTypes->values);
        $this->assertCount(2, $filters->categoryForms->values);
        $this->assertCount(3, $filters->regionalForms->values);
        $this->assertCount(2, $filters->specialForms->values);
        $this->assertCount(1, $filters->variantForms->values);
        $this->assertCount(1, $filters->catchStates->values);
        $this->assertCount(1, $filters->originalGameBundles->values);
        $this->assertCount(1, $filters->gameBundleAvailabilities->values);
        $this->assertCount(1, $filters->gameBundleShinyAvailabilities->values);
        $this->assertCount(2, $filters->families->values);
        $this->assertCount(1, $filters->collectionAvailabilities->values);

        $this->assertEmpty($filters->primaryTypes->negativeValues);
        $this->assertEmpty($filters->secondaryTypes->negativeValues);
        $this->assertEmpty($filters->anyTypes->negativeValues);
        $this->assertEmpty($filters->categoryForms->negativeValues);
        $this->assertEmpty($filters->regionalForms->negativeValues);
        $this->assertEmpty($filters->specialForms->negativeValues);
        $this->assertEmpty($filters->variantForms->negativeValues);
        $this->assertEmpty($filters->catchStates->negativeValues);
        $this->assertEmpty($filters->originalGameBundles->negativeValues);
        $this->assertEmpty($filters->gameBundleAvailabilities->negativeValues);
        $this->assertEmpty($filters->gameBundleShinyAvailabilities->negativeValues);
        $this->assertEmpty($filters->families->negativeValues);
        $this->assertEmpty($filters->collectionAvailabilities->negativeValues);
    }

    public function testCreateFromArrayWithNegative(): void
    {
        $filters = AlbumFilters::createFromArray([
            'primaryTypes' => ['fire', 'water'],
            'secondaryTypes' => ['water', 'fire'],
            'anyTypes' => ['normal'],
            'categoryForms' => ['starter', 'finisher'],
            'regionalForms' => ['provence', 'sud', 'mer'],
            'specialForms' => ['banana', 'orange'],
            'variantForms' => ['gender'],
            'catchStates' => ['!maybe'],
            'originalGameBundles' => ['redgreenblueyellow'],
            'gameBundleAvailabilities' => ['!sunmoon'],
            'gameBundleShinyAvailabilities' => ['!ultrasunutramoon'],
            'families' => ['pichu', 'eevee'],
            'collectionAvailabilities' => ['swshdens'],
        ]);

        $this->assertInstanceOf(AlbumFilters::class, $filters);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->primaryTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->secondaryTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->anyTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->categoryForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->regionalForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->specialForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->variantForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->catchStates);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->originalGameBundles);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->gameBundleAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->gameBundleShinyAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->families);
        $this->assertInstanceOf(AlbumFilterValues::class, $filters->collectionAvailabilities);

        $this->assertCount(2, $filters->primaryTypes->values);
        $this->assertCount(2, $filters->secondaryTypes->values);
        $this->assertCount(1, $filters->anyTypes->values);
        $this->assertCount(2, $filters->categoryForms->values);
        $this->assertCount(3, $filters->regionalForms->values);
        $this->assertCount(2, $filters->specialForms->values);
        $this->assertCount(1, $filters->variantForms->values);
        $this->assertCount(0, $filters->catchStates->values);
        $this->assertCount(1, $filters->originalGameBundles->values);
        $this->assertCount(0, $filters->gameBundleAvailabilities->values);
        $this->assertCount(0, $filters->gameBundleShinyAvailabilities->values);
        $this->assertCount(2, $filters->families->values);
        $this->assertCount(1, $filters->collectionAvailabilities->values);

        $this->assertCount(0, $filters->primaryTypes->negativeValues);
        $this->assertCount(0, $filters->secondaryTypes->negativeValues);
        $this->assertCount(0, $filters->anyTypes->negativeValues);
        $this->assertCount(0, $filters->categoryForms->negativeValues);
        $this->assertCount(0, $filters->regionalForms->negativeValues);
        $this->assertCount(0, $filters->specialForms->negativeValues);
        $this->assertCount(0, $filters->variantForms->negativeValues);
        $this->assertCount(1, $filters->catchStates->negativeValues);
        $this->assertCount(0, $filters->originalGameBundles->negativeValues);
        $this->assertCount(1, $filters->gameBundleAvailabilities->negativeValues);
        $this->assertCount(1, $filters->gameBundleShinyAvailabilities->negativeValues);
        $this->assertCount(0, $filters->families->negativeValues);
        $this->assertCount(0, $filters->collectionAvailabilities->negativeValues);
    }

    public function testNormalizer(): void
    {
        $filterValues = AlbumFilters::normalizer(['a', 'b', 'c']);

        $this->assertInstanceOf(AlbumFilterValues::class, $filterValues);
        $this->assertCount(3, $filterValues->values);
        $this->assertCount(0, $filterValues->negativeValues);
        $this->assertFalse($filterValues->hasNull());
    }

    public function testNormalizerWithNullValue(): void
    {
        $filterValues = AlbumFilters::normalizer(['a', 'null', 'c']);

        $this->assertInstanceOf(AlbumFilterValues::class, $filterValues);
        $this->assertCount(3, $filterValues->values);
        $this->assertCount(0, $filterValues->negativeValues);
        $this->assertTrue($filterValues->hasNull());
    }

    public function testNormalizerWithEmptyValue(): void
    {
        $filterValues = AlbumFilters::normalizer(['a', '', 'c']);

        $this->assertInstanceOf(AlbumFilterValues::class, $filterValues);
        $this->assertCount(2, $filterValues->values);
        $this->assertCount(0, $filterValues->negativeValues);
        $this->assertFalse($filterValues->hasNull());
    }

    public function testNormalizerWithEmptyNegativeValue(): void
    {
        $filterValues = AlbumFilters::normalizer(['a', '!b', 'c']);

        $this->assertInstanceOf(AlbumFilterValues::class, $filterValues);
        $this->assertCount(2, $filterValues->values);
        $this->assertCount(1, $filterValues->negativeValues);
        $this->assertFalse($filterValues->hasNull());
    }
}
