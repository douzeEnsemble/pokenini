<?php

declare(strict_types=1);

namespace App\Api\Command;

use App\Api\ActionStarter\CalculateDexAvailabilitiesActionStarter;
use App\Api\Service\CalculatorService\DexAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(name: 'app:calculate:dex_availabilities')]
class CalculateDexAvailabilitiesCommand extends AbstractCalculateCommand
{
    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        CalculateDexAvailabilitiesActionStarter $actionStarter,
        DexAvailabilitiesCalculatorService $calculatorService,
    ) {
        parent::__construct($translator, $entityManager, $actionStarter, $calculatorService);
    }

    #[\Override]
    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to update dex availabilities')
        ;
    }

    #[\Override]
    protected function getCommandName(): string
    {
        return 'app:calculate:dex_availabilities';
    }
}
