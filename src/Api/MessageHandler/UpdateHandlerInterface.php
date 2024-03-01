<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\AbstractActionMessage;

interface UpdateHandlerInterface
{
    public function update(AbstractActionMessage $message): void;
}
