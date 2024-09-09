<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdateGamesAndDex;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\MessageHandler\UpdateGamesAndDexHandler;
use App\Api\MessageHandler\UpdateHandlerInterface;
use App\Api\Service\UpdaterService\GamesAndDexUpdaterService;
use App\Api\Service\UpdaterService\UpdaterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(UpdateGamesAndDexHandler::class)]
#[UsesClass(GamesAndDexUpdaterService::class)]
#[UsesClass(UpdateGamesAndDex::class)]
#[CoversTrait(CalculateHandlerTrait::class)]
#[CoversTrait(ActionEnderTrait::class)]
class UpdateGamesAndDexHandlerTest extends AbstractTestUpdateHandler
{
    public function getServiceClass(): string
    {
        return GamesAndDexUpdaterService::class;
    }

    /**
     * @param GamesAndDexUpdaterService $updaterService
     */
    public function getHandler(
        UpdaterServiceInterface $updaterService,
        EntityManagerInterface $entityManager,
    ): UpdateHandlerInterface {
        return new UpdateGamesAndDexHandler(
            $updaterService,
            $entityManager,
        );
    }

    public function getMessage(): AbstractActionMessage
    {
        return new UpdateGamesAndDex('12');
    }
}
