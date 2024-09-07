<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdateRegionalDexNumbers;
use App\Api\MessageHandler\UpdateHandlerInterface;
use App\Api\MessageHandler\UpdateRegionalDexNumbersHandler;
use App\Api\Service\UpdaterService\RegionalDexNumbersUpdaterService;
use App\Api\Service\UpdaterService\UpdaterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class UpdateRegionalDexNumbersHandlerTest extends AbstractTestUpdateHandler
{
    public function getServiceClass(): string
    {
        return RegionalDexNumbersUpdaterService::class;
    }

    /**
     * @param RegionalDexNumbersUpdaterService $updaterService
     */
    public function getHandler(
        UpdaterServiceInterface $updaterService,
        EntityManagerInterface $entityManager,
    ): UpdateHandlerInterface {
        return new UpdateRegionalDexNumbersHandler(
            $updaterService,
            $entityManager,
        );
    }

    public function getMessage(): AbstractActionMessage
    {
        return new UpdateRegionalDexNumbers('12');
    }
}
