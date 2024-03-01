<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\CalculateGameBundlesShiniesAvailabilities;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\Service\CalculatorService\GameBundlesShiniesAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CalculateGameBundlesShiniesAvailabilitiesHandler implements CalculateHandlerInterface
{
    use CalculateHandlerTrait;

    public function __construct(
        private readonly GameBundlesShiniesAvailabilitiesCalculatorService $calculatorService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CalculateGameBundlesShiniesAvailabilities $message): void
    {
        $this->calculate($message);
    }
}
