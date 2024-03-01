<?php

declare(strict_types=1);

namespace App\Api\ActionEnder;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\Entity\ActionLog;
use App\Api\Message\ActionMessageInterface;
use App\Api\Repository\ActionLogsRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

trait ActionEnderTrait
{
    private readonly EntityManagerInterface $entityManager;

    protected function endActionLog(
        ActionMessageInterface $message,
        Report $report,
    ): void {
        $actionLog = $this->findActionLog($message);

        $actionLog->reportData = (string) json_encode($report);

        $this->crimpActionLog($actionLog);
    }

    protected function endInErrorActionLog(
        ActionMessageInterface $message,
        string $errorTrace,
    ): void {
        $actionLog = $this->findActionLog($message);

        $actionLog->errorTrace = $errorTrace;

        $this->crimpActionLog($actionLog);
    }

    private function findActionLog(
        ActionMessageInterface $message
    ): ActionLog {
        /** @var ActionLogsRepository $repo */
        $repo = $this->entityManager->getRepository(ActionLog::class);

        /** @var ?ActionLog $actionLog */
        $actionLog = $repo->find($message->getActionId());

        if (null === $actionLog) {
            throw new RuntimeException("Can't find ActionLog #{$message->getActionId()}");
        }

        return $actionLog;
    }

    private function crimpActionLog(ActionLog $actionLog): void
    {
        $actionLog->doneAt = new \DateTime();

        $this->entityManager->flush();
    }
}
