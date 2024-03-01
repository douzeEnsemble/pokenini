<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Message;

use App\Api\Message\CalculateGameBundlesAvailabilities;
use PHPUnit\Framework\TestCase;

class CalculateGameBundlesAvailabilitiesTest extends TestCase
{
    public function testSerialize(): void
    {
        $message = new CalculateGameBundlesAvailabilities('12');

        $this->assertEquals(
            'O:50:"App\Api\Message\CalculateGameBundlesAvailabilities":1:{s:8:"actionId";s:2:"12";}',
            serialize($message)
        );
    }
}
