<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO;

use App\Api\DTO\PokemonElo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(PokemonElo::class)]
class PokemonEloTest extends TestCase
{
    public function testOk(): void
    {
        $attributes = new PokemonElo('pikachu', 12);

        $this->assertSame('pikachu', $attributes->getPokemonSlug());
        $this->assertSame(12, $attributes->getElo());
    }
}
