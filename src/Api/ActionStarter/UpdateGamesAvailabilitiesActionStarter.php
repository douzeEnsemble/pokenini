<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\UpdateGamesAvailabilities;

final class UpdateGamesAvailabilitiesActionStarter extends AbstractActionStarter
{
    protected function getMessageClass(): string
    {
        return UpdateGamesAvailabilities::class;
    }

    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new UpdateGamesAvailabilities($identifier);
    }
}
