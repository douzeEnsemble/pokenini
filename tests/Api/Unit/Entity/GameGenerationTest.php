<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\GameGeneration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GameGeneration::class)]
class GameGenerationTest extends TestCase
{
    public function testGetNumber(): void
    {
        $generation = new GameGeneration();
        $generation->name = '12';

        $this->assertSame(12, $generation->getNumber());
    }
}
