<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdateRegionalDexNumbers;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\MessageHandler\UpdateHandlerInterface;
use App\Api\MessageHandler\UpdateRegionalDexNumbersHandler;
use App\Api\Service\UpdaterService\RegionalDexNumbersUpdaterService;
use App\Api\Service\UpdaterService\UpdaterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(UpdateRegionalDexNumbersHandler::class)]
#[UsesClass(RegionalDexNumbersUpdaterService::class)]
#[UsesClass(UpdateRegionalDexNumbers::class)]
#[CoversTrait(CalculateHandlerTrait::class)]
#[CoversTrait(ActionEnderTrait::class)]
class UpdateRegionalDexNumbersHandlerTest extends AbstractTestUpdateHandler
{
    #[\Override]
    public function getServiceClass(): string
    {
        return RegionalDexNumbersUpdaterService::class;
    }

    /**
     * @param RegionalDexNumbersUpdaterService $updaterService
     */
    #[\Override]
    public function getHandler(
        UpdaterServiceInterface $updaterService,
        EntityManagerInterface $entityManager,
    ): UpdateHandlerInterface {
        return new UpdateRegionalDexNumbersHandler(
            $updaterService,
            $entityManager,
        );
    }

    #[\Override]
    public function getMessage(): AbstractActionMessage
    {
        return new UpdateRegionalDexNumbers('12');
    }
}
