<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity\Trait;

use App\Api\Entity\Traits\BaseEntityTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class BaseEntityTraitTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = $this->getObjectForTrait(BaseEntityTrait::class);

        $this->assertNull($entity->getIdentifier());
    }
}
