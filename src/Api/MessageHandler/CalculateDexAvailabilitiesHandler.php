<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\CalculateDexAvailabilities;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\Service\CalculatorService\DexAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CalculateDexAvailabilitiesHandler implements CalculateHandlerInterface
{
    use CalculateHandlerTrait;

    public function __construct(
        private readonly DexAvailabilitiesCalculatorService $calculatorService,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(CalculateDexAvailabilities $message): void
    {
        $this->calculate($message);
    }
}
