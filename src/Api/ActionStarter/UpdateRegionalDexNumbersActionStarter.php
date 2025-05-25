<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\UpdateRegionalDexNumbers;

final class UpdateRegionalDexNumbersActionStarter extends AbstractActionStarter
{
    #[\Override]
    protected function getMessageClass(): string
    {
        return UpdateRegionalDexNumbers::class;
    }

    #[\Override]
    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new UpdateRegionalDexNumbers($identifier);
    }
}
