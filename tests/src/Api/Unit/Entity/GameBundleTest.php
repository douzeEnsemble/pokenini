<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\GameBundle;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GameBundle::class)]
class GameBundleTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = new GameBundle();

        $this->assertNull($entity->getIdentifier());
    }

    public function testConvertToString(): void
    {
        $entity = new GameBundle();
        $entity->name = 'Douze';

        $this->assertEquals('Douze', (string) $entity);
        $this->assertEquals('Douze', $entity->__toString());
    }
}
