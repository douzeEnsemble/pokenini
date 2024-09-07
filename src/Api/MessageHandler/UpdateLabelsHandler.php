<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\UpdateLabels;
use App\Api\MessageHandler\Traits\UpdateHandlerTrait;
use App\Api\Service\UpdaterService\LabelsUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateLabelsHandler implements UpdateHandlerInterface
{
    use UpdateHandlerTrait;

    public function __construct(
        private readonly LabelsUpdaterService $updaterService,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(UpdateLabels $message): void
    {
        $this->update($message);
    }
}
