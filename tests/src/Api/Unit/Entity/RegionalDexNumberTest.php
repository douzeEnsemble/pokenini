<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\RegionalDexNumber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(RegionalDexNumber::class)]
class RegionalDexNumberTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = new RegionalDexNumber();

        $this->assertNull($entity->getIdentifier());
    }
}
