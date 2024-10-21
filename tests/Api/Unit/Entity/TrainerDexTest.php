<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\TrainerDex;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(TrainerDex::class)]
class TrainerDexTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = new TrainerDex();

        $this->assertNull($entity->getIdentifier());
    }

    public function testConvertToString(): void
    {
        $entity = new TrainerDex();
        $entity->name = 'Douze';

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Object of class App\Api\Entity\TrainerDex could not be converted to string');

        (string) $entity;
    }
}
