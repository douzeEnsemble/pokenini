<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Message;

use App\Api\Message\CalculateDexAvailabilities;
use PHPUnit\Framework\TestCase;

class CalculateDexAvailabilitiesTest extends TestCase
{
    public function testSerialize(): void
    {
        $message = new CalculateDexAvailabilities('12');

        $this->assertEquals(
            'O:42:"App\Api\Message\CalculateDexAvailabilities":1:{s:8:"actionId";s:2:"12";}',
            serialize($message)
        );
    }
}
