<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO;

use App\Api\DTO\GamesAvailabilities;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class GamesAvailabilitiesTest extends TestCase
{
    public function testGet(): void
    {
        $object = new GamesAvailabilities([
            'a' => true,
            'b' => false,
        ]);

        $this->assertTrue($object->a);
        $this->assertFalse($object->b);
    }

    public function testSet(): void
    {
        $this->expectException(\Exception::class);

        $object = new GamesAvailabilities([]);

        $object->c = true;
    }

    public function testIsset(): void
    {
        $object = new GamesAvailabilities([
            'a' => true,
        ]);

        $this->assertTrue(isset($object->a));
        $this->assertFalse(isset($object->b));
    }

    public function testAll(): void
    {
        $object = new GamesAvailabilities([
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
