<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Message;

use App\Api\Message\UpdatePokemons;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class UpdatePokemonsTest extends TestCase
{
    public function testSerialize(): void
    {
        $message = new UpdatePokemons('12');

        $this->assertEquals(
            'O:30:"App\Api\Message\UpdatePokemons":1:{s:8:"actionId";s:2:"12";}',
            serialize($message)
        );
    }
}
