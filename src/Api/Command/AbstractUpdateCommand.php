<?php

declare(strict_types=1);

namespace App\Api\Command;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\ActionStarter\ActionStarterInterface;
use App\Api\DTO\DataChangeReport\Statistic;
use App\Api\Service\UpdaterService\UpdaterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractUpdateCommand extends Command
{
    use ActionEnderTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $entityManager,
        protected readonly ActionStarterInterface $actionStarter,
        protected readonly UpdaterServiceInterface $updaterService
    ) {
        parent::__construct($this->getCommandName());
    }

    abstract protected function getCommandName(): string;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = $this->actionStarter->start();

        $this->updaterService->execute();

        $report = $this->updaterService->getReport();

        $this->endActionLog($message, $report);

        /** @var Statistic $statistic */
        foreach ($report->detail as $statistic) {
            $label = $this->translator->trans("update.{$statistic->slug}", [], 'api');

            $output->writeln("<info>{$statistic->count} {$label} updated</info>");
        }

        return Command::SUCCESS;
    }
}
