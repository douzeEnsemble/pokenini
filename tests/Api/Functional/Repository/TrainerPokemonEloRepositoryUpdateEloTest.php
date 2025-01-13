<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\TrainerPokemonEloRepository;
use App\Tests\Api\Common\Traits\GetterTrait\GetTrainerPokemonEloTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(TrainerPokemonEloRepository::class)]
class TrainerPokemonEloRepositoryUpdateEloTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use GetTrainerPokemonEloTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testUpdateElo(): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $repo->updateElo(4556, '7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', '', 'bulbasaur', 1);

        $this->assertSame(
            [
                'elo' => 4556,
                'count' => 1,
            ],
            $this->getEloAndCount('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', '', 'bulbasaur'),
        );
    }

    public function testUpdateNewElo(): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $repo->updateElo(1212, '7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', '', 'butterfree-gmax', -1);

        $this->assertSame(
            [
                'elo' => 1212,
                'count' => -1,
            ],
            $this->getEloAndCount('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', '', 'butterfree-gmax'),
        );
    }
}
