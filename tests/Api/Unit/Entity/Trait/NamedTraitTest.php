<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity\Trait;

use App\Api\Entity\Traits\NamedTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(NamedTrait::class)]
class NamedTraitTest extends TestCase
{
    public function testGetIdentifier(): void
    {
        $entity = $this->getObjectForTrait(NamedTrait::class);
        $entity->name = 'Douze';

        $this->assertEquals('Douze', (string) $entity);
        $this->assertEquals('Douze', $entity->__toString());
    }
}
