<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\CalculatePokemonAvailabilities;
use App\Api\MessageHandler\CalculateHandlerInterface;
use App\Api\MessageHandler\CalculatePokemonAvailabilitiesHandler;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\Service\CalculatorService\CalculatorServiceInterface;
use App\Api\Service\CalculatorService\PokemonAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(CalculatePokemonAvailabilitiesHandler::class)]
#[UsesClass(PokemonAvailabilitiesCalculatorService::class)]
#[UsesClass(CalculatePokemonAvailabilities::class)]
#[CoversTrait(CalculateHandlerTrait::class)]
#[CoversTrait(ActionEnderTrait::class)]
class CalculatePokemonAvailabilitiesHandlerTest extends AbstractTestCalculateHandler
{
    #[\Override]
    public function getServiceClass(): string
    {
        return PokemonAvailabilitiesCalculatorService::class;
    }

    /**
     * @param PokemonAvailabilitiesCalculatorService $calculatorService
     */
    #[\Override]
    public function getHandler(
        CalculatorServiceInterface $calculatorService,
        EntityManagerInterface $entityManager,
    ): CalculateHandlerInterface {
        return new CalculatePokemonAvailabilitiesHandler(
            $calculatorService,
            $entityManager,
        );
    }

    #[\Override]
    public function getMessage(): AbstractActionMessage
    {
        return new CalculatePokemonAvailabilities('12');
    }
}
