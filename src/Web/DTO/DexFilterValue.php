<?php

declare(strict_types=1);

namespace App\Web\DTO;

final class DexFilterValue
{
    public function __construct(public ?bool $value) {}
}
