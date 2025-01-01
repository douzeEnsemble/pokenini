<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Service;

use App\Api\Repository\TrainerPokemonEloRepository;
use App\Api\Service\UpdateTrainerPokemonEloService;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameBundleAvailabilityTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(UpdateTrainerPokemonEloService::class)]
class UpdateTrainerPokemonEloServiceTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountGameBundleAvailabilityTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testUpdateElo(): void
    {
        /** @var UpdateTrainerPokemonEloService $service */
        $service = static::getContainer()->get(UpdateTrainerPokemonEloService::class);

        $service->updateElo(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            '',
            'bulbasaur',
            'ivysaur',
        );

        /** @var TrainerPokemonEloRepository $trainerPokemonEloRepo */
        $trainerPokemonEloRepo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $this->assertSame(
            1026,
            $trainerPokemonEloRepo->getElo(
                '7b52009b64fd0a2a49e6d8a939753077792b0554',
                '',
                'bulbasaur',
            )
        );
        $this->assertSame(
            1004,
            $trainerPokemonEloRepo->getElo(
                '7b52009b64fd0a2a49e6d8a939753077792b0554',
                '',
                'ivysaur',
            )
        );
    }

    public function testUpdateEloReverse(): void
    {
        /** @var UpdateTrainerPokemonEloService $service */
        $service = static::getContainer()->get(UpdateTrainerPokemonEloService::class);

        $service->updateElo(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            '',
            'ivysaur',
            'bulbasaur',
        );

        /** @var TrainerPokemonEloRepository $trainerPokemonEloRepo */
        $trainerPokemonEloRepo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $this->assertSame(
            1036,
            $trainerPokemonEloRepo->getElo(
                '7b52009b64fd0a2a49e6d8a939753077792b0554',
                '',
                'ivysaur',
            )
        );
        $this->assertSame(
            994,
            $trainerPokemonEloRepo->getElo(
                '7b52009b64fd0a2a49e6d8a939753077792b0554',
                '',
                'bulbasaur',
            )
        );
    }
}
