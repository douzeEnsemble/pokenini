<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\Pokedex;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Pokedex::class)]
class PokedexTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = new Pokedex();

        $this->assertNull($entity->getIdentifier());
    }
}
