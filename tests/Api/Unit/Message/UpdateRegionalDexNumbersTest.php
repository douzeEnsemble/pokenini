<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Message;

use App\Api\Message\UpdateRegionalDexNumbers;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(UpdateRegionalDexNumbers::class)]
class UpdateRegionalDexNumbersTest extends TestCase
{
    public function testSerialize(): void
    {
        $message = new UpdateRegionalDexNumbers('12');

        $this->assertEquals(
            'O:40:"App\Api\Message\UpdateRegionalDexNumbers":1:{s:8:"actionId";s:2:"12";}',
            serialize($message)
        );
    }
}
