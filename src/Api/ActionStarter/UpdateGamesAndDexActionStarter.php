<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\UpdateGamesAndDex;

final class UpdateGamesAndDexActionStarter extends AbstractActionStarter
{
    protected function getMessageClass(): string
    {
        return UpdateGamesAndDex::class;
    }

    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new UpdateGamesAndDex($identifier);
    }
}
