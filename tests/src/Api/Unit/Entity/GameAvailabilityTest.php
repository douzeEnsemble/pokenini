<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\GameAvailability;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GameAvailability::class)]
class GameAvailabilityTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = new GameAvailability();

        $this->assertNull($entity->getIdentifier());
    }
}
