<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\CalculateGameBundlesAvailabilities;

final class CalculateGameBundlesAvailabilitiesActionStarter extends AbstractActionStarter
{
    #[\Override]
    protected function getMessageClass(): string
    {
        return CalculateGameBundlesAvailabilities::class;
    }

    #[\Override]
    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new CalculateGameBundlesAvailabilities($identifier);
    }
}
