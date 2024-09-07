<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\Message\AbstractActionMessage;
use App\Api\Message\CalculateGameBundlesAvailabilities;
use App\Api\MessageHandler\CalculateGameBundlesAvailabilitiesHandler;
use App\Api\MessageHandler\CalculateHandlerInterface;
use App\Api\Service\CalculatorService\CalculatorServiceInterface;
use App\Api\Service\CalculatorService\GameBundlesAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class CalculateGameBundlesAvailabilitiesHandlerTest extends AbstractTestCalculateHandler
{
    public function getServiceClass(): string
    {
        return GameBundlesAvailabilitiesCalculatorService::class;
    }

    /**
     * @param GameBundlesAvailabilitiesCalculatorService $calculatorService
     */
    public function getHandler(
        CalculatorServiceInterface $calculatorService,
        EntityManagerInterface $entityManager,
    ): CalculateHandlerInterface {
        return new CalculateGameBundlesAvailabilitiesHandler(
            $calculatorService,
            $entityManager,
        );
    }

    public function getMessage(): AbstractActionMessage
    {
        return new CalculateGameBundlesAvailabilities('12');
    }
}
