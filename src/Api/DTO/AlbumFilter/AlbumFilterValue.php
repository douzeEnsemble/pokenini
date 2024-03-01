<?php

declare(strict_types=1);

namespace App\Api\DTO\AlbumFilter;

final class AlbumFilterValue
{
    public function __construct(public ?string $value)
    {
    }
}
