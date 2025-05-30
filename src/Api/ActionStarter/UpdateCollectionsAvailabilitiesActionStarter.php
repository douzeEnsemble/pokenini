<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\UpdateCollectionsAvailabilities;

final class UpdateCollectionsAvailabilitiesActionStarter extends AbstractActionStarter
{
    #[\Override]
    protected function getMessageClass(): string
    {
        return UpdateCollectionsAvailabilities::class;
    }

    #[\Override]
    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new UpdateCollectionsAvailabilities($identifier);
    }
}
