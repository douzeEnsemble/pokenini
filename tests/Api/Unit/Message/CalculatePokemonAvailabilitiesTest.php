<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Message;

use App\Api\Message\CalculatePokemonAvailabilities;
use PHPUnit\Framework\TestCase;

class CalculatePokemonAvailabilitiesTest extends TestCase
{
    public function testSerialize(): void
    {
        $message = new CalculatePokemonAvailabilities('12');

        $this->assertEquals(
            'O:46:"App\Api\Message\CalculatePokemonAvailabilities":1:{s:8:"actionId";s:2:"12";}',
            serialize($message)
        );
    }
}
