<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Message;

use App\Api\Message\UpdateGamesAvailabilities;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(UpdateGamesAvailabilities::class)]
class UpdateGamesAvailabilitiesTest extends TestCase
{
    public function testSerialize(): void
    {
        $message = new UpdateGamesAvailabilities('12');

        $this->assertEquals(
            'O:41:"App\Api\Message\UpdateGamesAvailabilities":1:{s:8:"actionId";s:2:"12";}',
            serialize($message)
        );
    }
}
