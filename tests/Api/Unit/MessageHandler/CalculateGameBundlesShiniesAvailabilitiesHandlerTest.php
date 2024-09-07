<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\Message\AbstractActionMessage;
use App\Api\Message\CalculateGameBundlesShiniesAvailabilities;
use App\Api\MessageHandler\CalculateGameBundlesShiniesAvailabilitiesHandler;
use App\Api\MessageHandler\CalculateHandlerInterface;
use App\Api\Service\CalculatorService\CalculatorServiceInterface;
use App\Api\Service\CalculatorService\GameBundlesShiniesAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class CalculateGameBundlesShiniesAvailabilitiesHandlerTest extends AbstractTestCalculateHandler
{
    public function getServiceClass(): string
    {
        return GameBundlesShiniesAvailabilitiesCalculatorService::class;
    }

    /**
     * @param GameBundlesShiniesAvailabilitiesCalculatorService $calculatorService
     */
    public function getHandler(
        CalculatorServiceInterface $calculatorService,
        EntityManagerInterface $entityManager,
    ): CalculateHandlerInterface {
        return new CalculateGameBundlesShiniesAvailabilitiesHandler(
            $calculatorService,
            $entityManager,
        );
    }

    public function getMessage(): AbstractActionMessage
    {
        return new CalculateGameBundlesShiniesAvailabilities('12');
    }
}
