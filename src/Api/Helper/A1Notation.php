<?php

declare(strict_types=1);

namespace App\Api\Helper;

class A1Notation
{
    private const LOOKUP = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function fromIndex(int $rowIndex, int $columnIndex): string
    {
        $row = $rowIndex + 1;

        $column = self::indexToLetter($columnIndex);

        return $column . $row;
    }

    /**
     * @see https://www.anycodings.com/1questions/1268219/how-can-i-convert-an-integer-to-a1-notation
     */
    public static function indexToLetter(int $index): string
    {
        if ($index < strlen(self::LOOKUP)) {
            return self::LOOKUP[$index];
        }

        return self::indexToLetter((int) floor($index / strlen(self::LOOKUP)) - 1)
            . self::indexToLetter($index % strlen(self::LOOKUP));
    }
}
