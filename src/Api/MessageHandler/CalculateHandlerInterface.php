<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\AbstractActionMessage;

interface CalculateHandlerInterface
{
    public function calculate(AbstractActionMessage $message): void;
}
