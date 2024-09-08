<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\Message\AbstractActionMessage;
use App\Api\Message\CalculatePokemonAvailabilities;
use App\Api\MessageHandler\CalculateHandlerInterface;
use App\Api\MessageHandler\CalculatePokemonAvailabilitiesHandler;
use App\Api\Service\CalculatorService\CalculatorServiceInterface;
use App\Api\Service\CalculatorService\PokemonAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(CalculatePokemonAvailabilities::class)]
class CalculatePokemonAvailabilitiesHandlerTest extends AbstractTestCalculateHandler
{
    public function getServiceClass(): string
    {
        return PokemonAvailabilitiesCalculatorService::class;
    }

    /**
     * @param PokemonAvailabilitiesCalculatorService $calculatorService
     */
    public function getHandler(
        CalculatorServiceInterface $calculatorService,
        EntityManagerInterface $entityManager,
    ): CalculateHandlerInterface {
        return new CalculatePokemonAvailabilitiesHandler(
            $calculatorService,
            $entityManager,
        );
    }

    public function getMessage(): AbstractActionMessage
    {
        return new CalculatePokemonAvailabilities('12');
    }
}
