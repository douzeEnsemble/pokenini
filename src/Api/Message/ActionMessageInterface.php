<?php

declare(strict_types=1);

namespace App\Api\Message;

interface ActionMessageInterface
{
    public function getActionId(): string;
}
