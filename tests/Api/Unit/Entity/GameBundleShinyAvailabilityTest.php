<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\GameBundle;
use App\Api\Entity\GameBundleShinyAvailability;
use App\Api\Entity\Pokemon;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class GameBundleShinyAvailabilityTest extends TestCase
{
    public function testCreateAvailable(): void
    {
        $pokemon = new Pokemon();
        $pokemon->slug = 'Douze';

        $gameBundle = new GameBundle();
        $gameBundle->slug = 'Tic,Tac';

        $gameBundleShinyAvailability = GameBundleShinyAvailability::create(
            $pokemon,
            $gameBundle,
            true
        );

        $this->assertEquals($pokemon, $gameBundleShinyAvailability->pokemon);
        $this->assertEquals($gameBundle, $gameBundleShinyAvailability->bundle);
        $this->assertTrue($gameBundleShinyAvailability->isAvailable);
    }

    public function testCreateUnavailable(): void
    {
        $pokemon = new Pokemon();
        $pokemon->slug = 'Douze';

        $gameBundle = new GameBundle();
        $gameBundle->slug = 'Tic,Tac';

        $gameBundleShinyAvailability = GameBundleShinyAvailability::create(
            $pokemon,
            $gameBundle,
            false
        );

        $this->assertEquals($pokemon, $gameBundleShinyAvailability->pokemon);
        $this->assertEquals($gameBundle, $gameBundleShinyAvailability->bundle);
        $this->assertFalse($gameBundleShinyAvailability->isAvailable);
    }
}
