<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\PokemonAvailabilities;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(PokemonAvailabilities::class)]
class PokemonAvailabilitiesTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = new PokemonAvailabilities();

        $this->assertNull($entity->getIdentifier());
    }
}
