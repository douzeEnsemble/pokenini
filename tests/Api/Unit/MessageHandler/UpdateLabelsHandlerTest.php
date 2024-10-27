<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdateLabels;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\MessageHandler\UpdateHandlerInterface;
use App\Api\MessageHandler\UpdateLabelsHandler;
use App\Api\Service\UpdaterService\LabelsUpdaterService;
use App\Api\Service\UpdaterService\UpdaterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(UpdateLabelsHandler::class)]
#[UsesClass(LabelsUpdaterService::class)]
#[UsesClass(UpdateLabels::class)]
#[CoversClass(CalculateHandlerTrait::class)]
#[CoversClass(ActionEnderTrait::class)]
class UpdateLabelsHandlerTest extends AbstractTestUpdateHandler
{
    public function getServiceClass(): string
    {
        return LabelsUpdaterService::class;
    }

    /**
     * @param LabelsUpdaterService $updaterService
     */
    public function getHandler(
        UpdaterServiceInterface $updaterService,
        EntityManagerInterface $entityManager,
    ): UpdateHandlerInterface {
        return new UpdateLabelsHandler(
            $updaterService,
            $entityManager,
        );
    }

    public function getMessage(): AbstractActionMessage
    {
        return new UpdateLabels('12');
    }
}
