<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\CalculateGameBundlesShiniesAvailabilities;

final class CalculateGameBundlesShiniesAvailabilitiesActionStarter extends AbstractActionStarter
{
    #[\Override]
    protected function getMessageClass(): string
    {
        return CalculateGameBundlesShiniesAvailabilities::class;
    }

    #[\Override]
    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new CalculateGameBundlesShiniesAvailabilities($identifier);
    }
}
