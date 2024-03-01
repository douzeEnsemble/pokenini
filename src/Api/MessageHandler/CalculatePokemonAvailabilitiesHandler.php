<?php

declare(strict_types=1);

namespace App\Api\MessageHandler;

use App\Api\Message\CalculatePokemonAvailabilities;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\Service\CalculatorService\PokemonAvailabilitiesCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CalculatePokemonAvailabilitiesHandler implements CalculateHandlerInterface
{
    use CalculateHandlerTrait;

    public function __construct(
        private readonly PokemonAvailabilitiesCalculatorService $calculatorService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CalculatePokemonAvailabilities $message): void
    {
        $this->calculate($message);
    }
}
