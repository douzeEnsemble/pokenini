<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\Message\AbstractActionMessage;
use App\Api\Message\CalculateDexAvailabilities;
use App\Api\MessageHandler\CalculateDexAvailabilitiesHandler;
use App\Api\MessageHandler\CalculateHandlerInterface;
use App\Api\Service\CalculatorService\CalculatorServiceInterface;
use App\Api\Service\CalculatorService\DexAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;

class CalculateDexAvailabilitiesHandlerTest extends AbstractTestCalculateHandler
{
    public function getServiceClass(): string
    {
        return DexAvailabilitiesCalculatorService::class;
    }

    /**
     * @param DexAvailabilitiesCalculatorService $calculatorService
    **/
    public function getHandler(
        CalculatorServiceInterface $calculatorService,
        EntityManagerInterface $entityManager,
    ): CalculateHandlerInterface {
        return new CalculateDexAvailabilitiesHandler(
            $calculatorService,
            $entityManager,
        );
    }

    public function getMessage(): AbstractActionMessage
    {
        return new CalculateDexAvailabilities('12');
    }
}
