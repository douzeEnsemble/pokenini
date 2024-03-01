<?php

declare(strict_types=1);

namespace App\Api\Command;

use App\Api\ActionStarter\UpdatePokemonsActionStarter;
use App\Api\Service\UpdaterService\PokemonsUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(name: 'app:update:pokemons')]
final class UpdatePokemonsCommand extends AbstractUpdateCommand
{
    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        UpdatePokemonsActionStarter $actionStarter,
        PokemonsUpdaterService $updaterService,
    ) {
        parent::__construct($translator, $entityManager, $actionStarter, $updaterService);
    }

    protected function getCommandName(): string
    {
        return 'app:update:pokemons';
    }
}
