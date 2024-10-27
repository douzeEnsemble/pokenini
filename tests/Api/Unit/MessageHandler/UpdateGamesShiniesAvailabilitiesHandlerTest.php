<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdateGamesShiniesAvailabilities;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\MessageHandler\UpdateGamesShiniesAvailabilitiesHandler;
use App\Api\MessageHandler\UpdateHandlerInterface;
use App\Api\Service\UpdaterService\GamesShiniesAvailabilitiesUpdaterService;
use App\Api\Service\UpdaterService\UpdaterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(UpdateGamesShiniesAvailabilitiesHandler::class)]
#[UsesClass(GamesShiniesAvailabilitiesUpdaterService::class)]
#[UsesClass(UpdateGamesShiniesAvailabilities::class)]
#[CoversClass(CalculateHandlerTrait::class)]
#[CoversClass(ActionEnderTrait::class)]
class UpdateGamesShiniesAvailabilitiesHandlerTest extends AbstractTestUpdateHandler
{
    public function getServiceClass(): string
    {
        return GamesShiniesAvailabilitiesUpdaterService::class;
    }

    /**
     * @param GamesShiniesAvailabilitiesUpdaterService $updaterService
     */
    public function getHandler(
        UpdaterServiceInterface $updaterService,
        EntityManagerInterface $entityManager,
    ): UpdateHandlerInterface {
        return new UpdateGamesShiniesAvailabilitiesHandler(
            $updaterService,
            $entityManager,
        );
    }

    public function getMessage(): AbstractActionMessage
    {
        return new UpdateGamesShiniesAvailabilities('12');
    }
}
