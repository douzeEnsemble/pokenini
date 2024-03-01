<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\GameBundle;
use App\Api\Entity\GameBundleAvailability;
use App\Api\Entity\Pokemon;
use PHPUnit\Framework\TestCase;

class GameBundleAvailabilityTest extends TestCase
{
    public function testCreateAvailable(): void
    {
        $pokemon = new Pokemon();
        $pokemon->slug = 'Douze';

        $gameBundle = new GameBundle();
        $gameBundle->slug = 'Tic,Tac';

        $gameBundleAvailability = GameBundleAvailability::create(
            $pokemon,
            $gameBundle,
            true
        );

        $this->assertEquals($pokemon, $gameBundleAvailability->pokemon);
        $this->assertEquals($gameBundle, $gameBundleAvailability->bundle);
        $this->assertTrue($gameBundleAvailability->isAvailable);
    }

    public function testCreateUnavailable(): void
    {
        $pokemon = new Pokemon();
        $pokemon->slug = 'Douze';

        $gameBundle = new GameBundle();
        $gameBundle->slug = 'Tic,Tac';

        $gameBundleAvailability = GameBundleAvailability::create(
            $pokemon,
            $gameBundle,
            false
        );

        $this->assertEquals($pokemon, $gameBundleAvailability->pokemon);
        $this->assertEquals($gameBundle, $gameBundleAvailability->bundle);
        $this->assertFalse($gameBundleAvailability->isAvailable);
    }
}
