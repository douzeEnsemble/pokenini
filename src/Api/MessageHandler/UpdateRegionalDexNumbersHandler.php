<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\UpdateRegionalDexNumbers;
use App\Api\MessageHandler\Traits\UpdateHandlerTrait;
use App\Api\Service\UpdaterService\RegionalDexNumbersUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateRegionalDexNumbersHandler implements UpdateHandlerInterface
{
    use UpdateHandlerTrait;

    public function __construct(
        private readonly RegionalDexNumbersUpdaterService $updaterService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(UpdateRegionalDexNumbers $message): void
    {
        $this->update($message);
    }
}
