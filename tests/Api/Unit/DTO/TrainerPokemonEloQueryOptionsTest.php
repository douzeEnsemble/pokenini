<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO;

use App\Api\DTO\TrainerPokemonEloQueryOptions;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * @internal
 */
#[CoversClass(TrainerPokemonEloQueryOptions::class)]
class TrainerPokemonEloQueryOptionsTest extends TestCase
{
    public function testOk(): void
    {
        $attributes = new TrainerPokemonEloQueryOptions([
            'trainer_external_id' => '67865468',
            'election_slug' => 'douze',
            'count' => 10,
        ]);

        $this->assertSame('67865468', $attributes->trainerExternalId);
        $this->assertSame('douze', $attributes->electionSlug);
        $this->assertSame(10, $attributes->count);
    }

    public function testMissingElectionSlug(): void
    {
        $attributes = new TrainerPokemonEloQueryOptions([
            'trainer_external_id' => '67865468',
            'count' => 10,
        ]);

        $this->assertSame('67865468', $attributes->trainerExternalId);
        $this->assertSame('', $attributes->electionSlug);
        $this->assertSame(10, $attributes->count);
    }

    public function testWrongValueForTrainerExternalId(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new TrainerPokemonEloQueryOptions([
            'trainer_external_id' => 67865468,
            'count' => 10,
        ]);
    }

    public function testWrongValueForElectionSlug(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new TrainerPokemonEloQueryOptions([
            'trainer_external_id' => '67865468',
            'election_slug' => false,
            'count' => 10,
        ]);
    }

    public function testWrongValueForCount(): void
    {
        $attributes = new TrainerPokemonEloQueryOptions([
            'trainer_external_id' => '67865468',
            'election_slug' => '',
            'count' => '10',
        ]);

        $this->assertSame('67865468', $attributes->trainerExternalId);
        $this->assertSame('', $attributes->electionSlug);
        $this->assertSame(10, $attributes->count);
    }

    public function testAnotherValue(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        new TrainerPokemonEloQueryOptions([
            'trainer_external_id' => '67865468',
            'election_slug' => 'douze',
            'count' => 10,
            'other' => 'idk',
        ]);
    }
}
