<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\CatchState;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(CatchState::class)]
class CatchStateTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = new CatchState();

        $this->assertNull($entity->getIdentifier());
    }

    public function testConvertToString(): void
    {
        $entity = new CatchState();
        $entity->name = 'Douze';

        $this->assertEquals('Douze', (string) $entity);
        $this->assertEquals('Douze', $entity->__toString());
    }
}
