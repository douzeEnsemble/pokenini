<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\UpdatePokemons;
use App\Api\MessageHandler\Traits\UpdateHandlerTrait;
use App\Api\Service\UpdaterService\PokemonsUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdatePokemonsHandler implements UpdateHandlerInterface
{
    use UpdateHandlerTrait;

    public function __construct(
        private readonly PokemonsUpdaterService $updaterService,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(UpdatePokemons $message): void
    {
        $this->update($message);
    }
}
