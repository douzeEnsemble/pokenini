<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdateGamesCollectionsAndDex;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\MessageHandler\UpdateGamesCollectionsAndDexHandler;
use App\Api\MessageHandler\UpdateHandlerInterface;
use App\Api\Service\UpdaterService\GamesCollectionsAndDexUpdaterService;
use App\Api\Service\UpdaterService\UpdaterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(UpdateGamesCollectionsAndDexHandler::class)]
#[UsesClass(GamesCollectionsAndDexUpdaterService::class)]
#[UsesClass(UpdateGamesCollectionsAndDex::class)]
#[CoversTrait(CalculateHandlerTrait::class)]
#[CoversTrait(ActionEnderTrait::class)]
class UpdateGamesCollectionsAndDexHandlerTest extends AbstractTestUpdateHandler
{
    #[\Override]
    public function getServiceClass(): string
    {
        return GamesCollectionsAndDexUpdaterService::class;
    }

    /**
     * @param GamesCollectionsAndDexUpdaterService $updaterService
     */
    #[\Override]
    public function getHandler(
        UpdaterServiceInterface $updaterService,
        EntityManagerInterface $entityManager,
    ): UpdateHandlerInterface {
        return new UpdateGamesCollectionsAndDexHandler(
            $updaterService,
            $entityManager,
        );
    }

    #[\Override]
    public function getMessage(): AbstractActionMessage
    {
        return new UpdateGamesCollectionsAndDex('12');
    }
}
