<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\MessageHandler;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdatePokemons;
use App\Api\MessageHandler\Traits\CalculateHandlerTrait;
use App\Api\MessageHandler\UpdateHandlerInterface;
use App\Api\MessageHandler\UpdatePokemonsHandler;
use App\Api\Service\UpdaterService\PokemonsUpdaterService;
use App\Api\Service\UpdaterService\UpdaterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * @internal
 */
#[CoversClass(UpdatePokemonsHandler::class)]
#[UsesClass(PokemonsUpdaterService::class)]
#[UsesClass(UpdatePokemons::class)]
#[CoversClass(CalculateHandlerTrait::class)]
#[CoversClass(ActionEnderTrait::class)]
class UpdatePokemonsHandlerTest extends AbstractTestUpdateHandler
{
    public function getServiceClass(): string
    {
        return PokemonsUpdaterService::class;
    }

    /**
     * @param PokemonsUpdaterService $updaterService
     */
    public function getHandler(
        UpdaterServiceInterface $updaterService,
        EntityManagerInterface $entityManager,
    ): UpdateHandlerInterface {
        return new UpdatePokemonsHandler(
            $updaterService,
            $entityManager,
        );
    }

    public function getMessage(): AbstractActionMessage
    {
        return new UpdatePokemons('12');
    }
}
