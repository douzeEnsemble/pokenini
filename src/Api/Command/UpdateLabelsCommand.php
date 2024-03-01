<?php

declare(strict_types=1);

namespace App\Api\Command;

use App\Api\ActionStarter\UpdateLabelsActionStarter;
use App\Api\Service\UpdaterService\LabelsUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(name: 'app:update:labels')]
final class UpdateLabelsCommand extends AbstractUpdateCommand
{
    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        UpdateLabelsActionStarter $actionStarter,
        LabelsUpdaterService $updaterService,
    ) {
        parent::__construct($translator, $entityManager, $actionStarter, $updaterService);
    }

    protected function getCommandName(): string
    {
        return 'app:update:labels';
    }
}
