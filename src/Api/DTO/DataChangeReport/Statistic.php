<?php

declare(strict_types=1);

namespace App\Api\DTO\DataChangeReport;

final class Statistic
{
    public function __construct(
        public string $slug,
        public int $count = 0,
    ) {
    }

    public function increment(): int
    {
        return ++$this->count;
    }

    public function incrementBy(int $increment): int
    {
        return $this->count += $increment;
    }
}
