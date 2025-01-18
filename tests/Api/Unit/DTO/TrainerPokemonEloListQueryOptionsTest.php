<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO;

use App\Api\DTO\AlbumFilter\AlbumFilterValues;
use App\Api\DTO\TrainerPokemonEloListQueryOptions;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * @internal
 */
#[CoversClass(TrainerPokemonEloListQueryOptions::class)]
class TrainerPokemonEloListQueryOptionsTest extends TestCase
{
    public function testOk(): void
    {
        $attributes = new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => '67865468',
            'dex_slug' => 'demo',
            'election_slug' => 'douze',
            'count' => 12,
        ]);

        $this->assertSame('67865468', $attributes->trainerExternalId);
        $this->assertSame('demo', $attributes->dexSlug);
        $this->assertSame('douze', $attributes->electionSlug);
        $this->assertSame(12, $attributes->count);
    }

    public function testWithAlbumFilters(): void
    {
        $attributes = new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => '67865468',
            'dex_slug' => 'demo',
            'election_slug' => 'douze',
            'count' => 12,
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

        $this->assertSame('67865468', $attributes->trainerExternalId);
        $this->assertSame('demo', $attributes->dexSlug);
        $this->assertSame('douze', $attributes->electionSlug);
        $this->assertSame(12, $attributes->count);

        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->primaryTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->secondaryTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->anyTypes);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->categoryForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->regionalForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->specialForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->variantForms);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->catchStates);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->originalGameBundles);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->gameBundleAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->gameBundleShinyAvailabilities);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->families);
        $this->assertInstanceOf(AlbumFilterValues::class, $attributes->albumFilters->collectionAvailabilities);
    }

    public function testMissingTrainerExternalId(): void
    {
        $this->expectException(MissingOptionsException::class);
        new TrainerPokemonEloListQueryOptions([
            'dex_slug' => 'demo',
            'election_slug' => 'douze',
            'count' => 12,
        ]);
    }

    public function testMissingDexSlug(): void
    {
        $this->expectException(MissingOptionsException::class);
        new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => '67865468',
            'election_slug' => 'douze',
            'count' => 12,
        ]);
    }

    public function testMissingElectionSlug(): void
    {
        $attributes = new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => '67865468',
            'dex_slug' => 'demo',
            'count' => 12,
        ]);

        $this->assertSame('67865468', $attributes->trainerExternalId);
        $this->assertSame('demo', $attributes->dexSlug);
        $this->assertSame('', $attributes->electionSlug);
        $this->assertSame(12, $attributes->count);
    }

    public function testMissingCount(): void
    {
        $this->expectException(MissingOptionsException::class);
        new TrainerPokemonEloListQueryOptions([
            'dex_slug' => 'demo',
            'trainer_external_id' => '67865468',
            'election_slug' => 'douze',
        ]);
    }

    public function testWrongValueForTrainerExternalId(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => 67865468,
            'dex_slug' => 'demo',
            'election_slug' => 'douze',
            'count' => 12,
        ]);
    }

    public function testWrongValueForDexSlug(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => '67865468',
            'dex_slug' => 78,
            'election_slug' => 'douze',
            'count' => 12,
        ]);
    }

    public function testWrongValueForElectionSlug(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => '67865468',
            'dex_slug' => 'demo',
            'election_slug' => 4568,
            'count' => 12,
        ]);
    }

    public function testWrongValueForCount(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => '67865468',
            'dex_slug' => 'demo',
            'election_slug' => '',
            'count' => false,
        ]);
    }

    public function testCountNormalizer(): void
    {
        $attributes = new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => '67865468',
            'dex_slug' => 'demo',
            'election_slug' => 'douze',
            'count' => '12',
        ]);

        $this->assertSame('67865468', $attributes->trainerExternalId);
        $this->assertSame('demo', $attributes->dexSlug);
        $this->assertSame('douze', $attributes->electionSlug);
        $this->assertSame(12, $attributes->count);
    }

    public function testAnotherValue(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => '67865468',
            'dex_slug' => 'demo',
            'election_slug' => 'douze',
            'count' => 12,
            'other' => 'idk',
        ]);
    }
}
