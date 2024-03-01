<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service\UpdaterService;

use App\Api\Service\UpdaterService\PokemonsUpdaterService;
use App\Api\Updater\PokemonsUpdater;
use PHPUnit\Framework\TestCase;
use App\Api\DTO\DataChangeReport\Statistic;

class PokemonsUpdaterServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $pokemonsUpdater = $this->createMock(PokemonsUpdater::class);
        $pokemonsUpdater
            ->expects($this->once())
            ->method('execute')
        ;
        $pokemonsUpdater
            ->expects($this->once())
            ->method('getStatistic')
            ->willReturn(new Statistic('p'))
        ;

        $service = new PokemonsUpdaterService(
            $pokemonsUpdater
        );

        $service->execute();
    }
}
