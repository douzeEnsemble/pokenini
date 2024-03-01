<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\UpdateRegionalDexNumbers;

final class UpdateRegionalDexNumbersActionStarter extends AbstractActionStarter
{
    protected function getMessageClass(): string
    {
        return UpdateRegionalDexNumbers::class;
    }

    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new UpdateRegionalDexNumbers($identifier);
    }
}
