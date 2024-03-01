<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\CalculateGameBundlesAvailabilities;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\Service\CalculatorService\GameBundlesAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CalculateGameBundlesAvailabilitiesHandler implements CalculateHandlerInterface
{
    use CalculateHandlerTrait;

    public function __construct(
        private readonly GameBundlesAvailabilitiesCalculatorService $calculatorService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CalculateGameBundlesAvailabilities $message): void
    {
        $this->calculate($message);
    }
}
