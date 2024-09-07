<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\UpdateGamesAvailabilities;
use App\Api\MessageHandler\Traits\UpdateHandlerTrait;
use App\Api\Service\UpdaterService\GamesAvailabilitiesUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateGamesAvailabilitiesHandler implements UpdateHandlerInterface
{
    use UpdateHandlerTrait;

    public function __construct(
        private readonly GamesAvailabilitiesUpdaterService $updaterService,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(UpdateGamesAvailabilities $message): void
    {
        $this->update($message);
    }
}
