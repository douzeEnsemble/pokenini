<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\UpdatePokemons;

final class UpdatePokemonsActionStarter extends AbstractActionStarter
{
    protected function getMessageClass(): string
    {
        return UpdatePokemons::class;
    }

    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new UpdatePokemons($identifier);
    }
}
