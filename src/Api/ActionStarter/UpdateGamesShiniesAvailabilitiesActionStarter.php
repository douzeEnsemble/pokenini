<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\UpdateGamesShiniesAvailabilities;

final class UpdateGamesShiniesAvailabilitiesActionStarter extends AbstractActionStarter
{
    #[\Override]
    protected function getMessageClass(): string
    {
        return UpdateGamesShiniesAvailabilities::class;
    }

    #[\Override]
    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new UpdateGamesShiniesAvailabilities($identifier);
    }
}
