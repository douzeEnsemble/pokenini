<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\CalculateDexAvailabilities;

final class CalculateDexAvailabilitiesActionStarter extends AbstractActionStarter
{
    protected function getMessageClass(): string
    {
        return CalculateDexAvailabilities::class;
    }

    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new CalculateDexAvailabilities($identifier);
    }
}
