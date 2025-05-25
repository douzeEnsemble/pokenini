<?php

declare(strict_types=1);

namespace App\Api\Command;

use App\Api\ActionStarter\CalculatePokemonAvailabilitiesActionStarter;
use App\Api\Service\CalculatorService\PokemonAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(name: 'app:calculate:pokemon_availabilities')]
class CalculatePokemonAvailabilitiesCommand extends AbstractCalculateCommand
{
    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        CalculatePokemonAvailabilitiesActionStarter $actionStarter,
        PokemonAvailabilitiesCalculatorService $calculatorService,
    ) {
        parent::__construct($translator, $entityManager, $actionStarter, $calculatorService);
    }

    #[\Override]
    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to update pokemon availabilities')
        ;
    }

    #[\Override]
    protected function getCommandName(): string
    {
        return 'app:calculate:pokemon_availabilities';
    }
}
