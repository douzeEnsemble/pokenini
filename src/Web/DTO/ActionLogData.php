<?php

declare(strict_types=1);

namespace App\Web\DTO;

class ActionLogData
{
    public function __construct(
        public readonly string $item,
        public readonly ActionLog $current,
        public readonly ?ActionLog $last = null,
    ) {
    }
}
