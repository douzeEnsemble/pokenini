<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Message;

use App\Api\Message\UpdateGamesCollectionsAndDex;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(UpdateGamesCollectionsAndDex::class)]
class UpdateGamesCollectionsAndDexTest extends TestCase
{
    public function testSerialize(): void
    {
        $message = new UpdateGamesCollectionsAndDex('12');

        $this->assertEquals(
            'O:44:"App\Api\Message\UpdateGamesCollectionsAndDex":1:{s:8:"actionId";s:2:"12";}',
            serialize($message)
        );
    }
}
