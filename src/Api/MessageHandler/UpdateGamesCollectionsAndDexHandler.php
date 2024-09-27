<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\UpdateGamesCollectionsAndDex;
use App\Api\MessageHandler\Traits\UpdateHandlerTrait;
use App\Api\Service\UpdaterService\GamesCollectionsAndDexUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateGamesCollectionsAndDexHandler implements UpdateHandlerInterface
{
    use UpdateHandlerTrait;

    public function __construct(
        private readonly GamesCollectionsAndDexUpdaterService $updaterService,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(UpdateGamesCollectionsAndDex $message): void
    {
        $this->update($message);
    }
}
