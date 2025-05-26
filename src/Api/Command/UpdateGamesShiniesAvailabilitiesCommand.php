<?php

declare(strict_types=1);

namespace App\Api\Command;

use App\Api\ActionStarter\UpdateGamesShiniesAvailabilitiesActionStarter;
use App\Api\Service\UpdaterService\GamesShiniesAvailabilitiesUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(name: 'app:update:games_shinies_availabilities')]
final class UpdateGamesShiniesAvailabilitiesCommand extends AbstractUpdateCommand
{
    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        UpdateGamesShiniesAvailabilitiesActionStarter $actionStarter,
        GamesShiniesAvailabilitiesUpdaterService $updaterService,
    ) {
        parent::__construct($translator, $entityManager, $actionStarter, $updaterService);
    }

    #[\Override]
    protected function getCommandName(): string
    {
        return 'app:update:games_shinies_availabilities';
    }
}
