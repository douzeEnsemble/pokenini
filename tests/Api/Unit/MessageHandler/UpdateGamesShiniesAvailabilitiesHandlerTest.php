<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdateGamesShiniesAvailabilities;
use App\Api\MessageHandler\UpdateGamesShiniesAvailabilitiesHandler;
use App\Api\MessageHandler\UpdateHandlerInterface;
use App\Api\Service\UpdaterService\GamesShiniesAvailabilitiesUpdaterService;
use App\Api\Service\UpdaterService\UpdaterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @internal
 *
 * @coversNothing
 */
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
