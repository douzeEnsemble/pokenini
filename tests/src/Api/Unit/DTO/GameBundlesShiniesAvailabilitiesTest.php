<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO;

use App\Api\DTO\GameBundlesShiniesAvailabilities;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GameBundlesShiniesAvailabilities::class)]
class GameBundlesShiniesAvailabilitiesTest extends TestCase
{
    public function testGet(): void
    {
        $object = new GameBundlesShiniesAvailabilities([
            'a' => true,
            'b' => false,
        ]);

        $this->assertTrue($object->a);
        $this->assertFalse($object->b);
    }

    public function testSet(): void
    {
        $this->expectException(\Exception::class);

        $object = new GameBundlesShiniesAvailabilities([]);

        $object->c = true;
    }

    public function testIsset(): void
    {
        $object = new GameBundlesShiniesAvailabilities([
            'a' => true,
        ]);

        $this->assertTrue(isset($object->a));
        $this->assertFalse(isset($object->b));
    }

    public function testAll(): void
    {
        $object = new GameBundlesShiniesAvailabilities([
            'a' => true,
            'b' => false,
        ]);

        $this->assertEquals(
            [
                'a' => true,
                'b' => false,
            ],
            $object->all()
        );
    }
}
