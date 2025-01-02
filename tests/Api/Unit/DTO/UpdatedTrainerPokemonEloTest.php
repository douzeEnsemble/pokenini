<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO;

use App\Api\DTO\UpdatedTrainerPokemonElo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(UpdatedTrainerPokemonElo::class)]
class UpdatedTrainerPokemonEloTest extends TestCase
{
    public function testGetter(): void
    {
        $object = new UpdatedTrainerPokemonElo('pichu', 12, 'pikachu', 13);

        $this->assertSame('pichu', $object->getWinnerSlug());
        $this->assertSame(12, $object->getWinnerElo());
        $this->assertSame('pikachu', $object->getLoserSlug());
        $this->assertSame(13, $object->getLoserElo());
    }
}
