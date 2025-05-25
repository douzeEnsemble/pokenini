<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\CalculateDexAvailabilities;
use App\Api\MessageHandler\CalculateDexAvailabilitiesHandler;
use App\Api\MessageHandler\CalculateHandlerInterface;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\Service\CalculatorService\CalculatorServiceInterface;
use App\Api\Service\CalculatorService\DexAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(CalculateDexAvailabilitiesHandler::class)]
#[UsesClass(DexAvailabilitiesCalculatorService::class)]
#[UsesClass(CalculateDexAvailabilities::class)]
#[CoversClass(CalculateHandlerTrait::class)]
#[CoversClass(ActionEnderTrait::class)]
class CalculateDexAvailabilitiesHandlerTest extends AbstractTestCalculateHandler
{
    #[\Override]
    public function getServiceClass(): string
    {
        return DexAvailabilitiesCalculatorService::class;
    }

    /**
     * @param DexAvailabilitiesCalculatorService $calculatorService
     */
    #[\Override]
    public function getHandler(
        CalculatorServiceInterface $calculatorService,
        EntityManagerInterface $entityManager,
    ): CalculateHandlerInterface {
        return new CalculateDexAvailabilitiesHandler(
            $calculatorService,
            $entityManager,
        );
    }

    #[\Override]
    public function getMessage(): AbstractActionMessage
    {
        return new CalculateDexAvailabilities('12');
    }
}
