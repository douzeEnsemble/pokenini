<?php

declare(strict_types=1);

namespace App\Api\Command;

use App\Api\ActionStarter\UpdateGamesAndDexActionStarter;
use App\Api\Service\UpdaterService\GamesAndDexUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(name: 'app:update:games_and_dex')]
final class UpdateGamesAndDexCommand extends AbstractUpdateCommand
{
    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        UpdateGamesAndDexActionStarter $actionStarter,
        GamesAndDexUpdaterService $updaterService,
    ) {
        parent::__construct($translator, $entityManager, $actionStarter, $updaterService);
    }

    protected function getCommandName(): string
    {
        return 'app:update:games_and_dex';
    }
}
