<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Message;

use App\Api\Message\UpdateLabels;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class UpdateLabelsTest extends TestCase
{
    public function testSerialize(): void
    {
        $message = new UpdateLabels('12');

        $this->assertEquals(
            'O:28:"App\Api\Message\UpdateLabels":1:{s:8:"actionId";s:2:"12";}',
            serialize($message)
        );
    }
}
