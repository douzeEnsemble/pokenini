<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\CalculateGameBundlesShiniesAvailabilities;
use App\Api\MessageHandler\CalculateGameBundlesShiniesAvailabilitiesHandler;
use App\Api\MessageHandler\CalculateHandlerInterface;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\Service\CalculatorService\CalculatorServiceInterface;
use App\Api\Service\CalculatorService\GameBundlesShiniesAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(CalculateGameBundlesShiniesAvailabilitiesHandler::class)]
#[UsesClass(GameBundlesShiniesAvailabilitiesCalculatorService::class)]
#[UsesClass(CalculateGameBundlesShiniesAvailabilities::class)]
#[CoversClass(CalculateHandlerTrait::class)]
#[CoversClass(ActionEnderTrait::class)]
class CalculateGameBundlesShiniesAvailabilitiesHandlerTest extends AbstractTestCalculateHandler
{
    #[\Override]
    public function getServiceClass(): string
    {
        return GameBundlesShiniesAvailabilitiesCalculatorService::class;
    }

    /**
     * @param GameBundlesShiniesAvailabilitiesCalculatorService $calculatorService
     */
    #[\Override]
    public function getHandler(
        CalculatorServiceInterface $calculatorService,
        EntityManagerInterface $entityManager,
    ): CalculateHandlerInterface {
        return new CalculateGameBundlesShiniesAvailabilitiesHandler(
            $calculatorService,
            $entityManager,
        );
    }

    #[\Override]
    public function getMessage(): AbstractActionMessage
    {
        return new CalculateGameBundlesShiniesAvailabilities('12');
    }
}
