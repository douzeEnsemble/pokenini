<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity\Trait;

use App\Api\Entity\Traits\BaseEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(BaseEntityTrait::class)]
class BaseEntityTraitTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = $this->getObjectForTrait(BaseEntityTrait::class);

        $this->assertNull($entity->getIdentifier());
    }
}
