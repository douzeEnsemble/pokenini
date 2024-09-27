<?php

declare(strict_types=1);

namespace App\Api\Command;

use App\Api\ActionStarter\UpdateGamesCollectionsAndDexActionStarter;
use App\Api\Service\UpdaterService\GamesCollectionsAndDexUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(name: 'app:update:games_collections_and_dex')]
final class UpdateGamesCollectionsAndDexCommand extends AbstractUpdateCommand
{
    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        UpdateGamesCollectionsAndDexActionStarter $actionStarter,
        GamesCollectionsAndDexUpdaterService $updaterService,
    ) {
        parent::__construct($translator, $entityManager, $actionStarter, $updaterService);
    }

    protected function getCommandName(): string
    {
        return 'app:update:games_collections_and_dex';
    }
}
