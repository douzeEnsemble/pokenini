<?php

declare(strict_types=1);

namespace App\Api\DTO\AlbumFilter;

final class AlbumFilterValues
{
    /** @var AlbumFilterValue[] */
    public array $values = [];

    /**
     * @param null[]|string[] $values
     */
    public function __construct(array $values)
    {
        foreach ($values as $value) {
            $this->values[] = new AlbumFilterValue($value);
        }
    }

    /**
     * @return null[]|string[]
     */
    public function extract(): array
    {
        $values = [];
        foreach ($this->values as $value) {
            $values[] = $value->value;
        }

        return $values;
    }

    public function hasNull(): bool
    {
        $hasNull = false;
        $index = 0;
        $count = count($this->values);

        while (!$hasNull && $index < $count) {
            $hasNull = is_null($this->values[$index++]->value);
        }

        return $hasNull;
    }
}
