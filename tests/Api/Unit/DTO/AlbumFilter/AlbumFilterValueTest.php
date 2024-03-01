<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO;

use App\Api\DTO\AlbumFilter\AlbumFilterValue;
use PHPUnit\Framework\TestCase;

class AlbumFilterValueTest extends TestCase
{
    public function testConstruct(): void
    {
        $albumFilterValue = new AlbumFilterValue('douze');

        $this->assertEquals('douze', $albumFilterValue->value);
    }
}
