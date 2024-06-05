<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Helper;

use App\Api\Helper\A1Notation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class A1NotationTest extends TestCase
{
    #[DataProvider('providerIndexToLetter')]
    public function testIndexToLetter(int $index, string $expected): void
    {
        $this->assertEquals($expected, A1Notation::indexToLetter($index));
    }

    #[DataProvider('providerIndexToA1Notation')]
    public function testFromIndex(int $rowIndex, int $columnIndex, string $expected): void
    {
        $this->assertEquals($expected, A1Notation::fromIndex($rowIndex, $columnIndex));
    }

    /**
     * @return int[][]|string[][]
     */
    public static function providerIndexToLetter(): array
    {
        return [
            [
                'index' => 0,
                'expected' => 'A',
            ],
            [
                'index' => 2,
                'expected' => 'C',
            ],
            [
                'index' => 27,
                'expected' => 'AB',
            ],
            [
                'index' => 53,
                'expected' => 'BB',
            ],
        ];
    }

    /**
     * @return int[][]|string[][]
     */
    public static function providerIndexToA1Notation(): array
    {
        return [
            [
                'rowIndex' => 0,
                'columnIndex' => 0,
                'expected' => 'A1',
            ],
            [
                'rowIndex' => 2,
                'columnIndex' => 0,
                'expected' => 'A3',
            ],
            [
                'rowIndex' => 2,
                'columnIndex' => 2,
                'expected' => 'C3',
            ],
            [
                'rowIndex' => 2,
                'columnIndex' => 27,
                'expected' => 'AB3',
            ],
            [
                'rowIndex' => 2,
                'columnIndex' => 53,
                'expected' => 'BB3',
            ],
        ];
    }
}
