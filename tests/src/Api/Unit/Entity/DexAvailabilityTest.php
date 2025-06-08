<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\DexAvailability;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(DexAvailability::class)]
class DexAvailabilityTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = new DexAvailability();

        $this->assertNull($entity->getIdentifier());
    }
}
