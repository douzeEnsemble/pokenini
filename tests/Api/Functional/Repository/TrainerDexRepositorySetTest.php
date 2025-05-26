<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\DTO\TrainerDexAttributes;
use App\Api\Repository\TrainerDexRepository;
use App\Tests\Api\Common\Traits\CounterTrait\CountTrainerDexTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(TrainerDexRepository::class)]
class TrainerDexRepositorySetTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountTrainerDexTrait;

    #[\Override]
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testExistingTrainerDex(): void
    {
        /** @var TrainerDexRepository $repo */
        $repo = static::getContainer()->get(TrainerDexRepository::class);

        $this->assertEquals(12, $this->getTrainerDexCount());

        $repo->set(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'redgreenblueyellow',
            new TrainerDexAttributes([])
        );

        $this->assertEquals(12, $this->getTrainerDexCount());
    }

    public function testNewTrainerDex(): void
    {
        /** @var TrainerDexRepository $repo */
        $repo = static::getContainer()->get(TrainerDexRepository::class);

        $this->assertEquals(12, $this->getTrainerDexCount());

        $repo->set(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'spoon',
            new TrainerDexAttributes([])
        );

        $this->assertEquals(13, $this->getTrainerDexCount());
    }

    public function testExistingCustomTrainerDex(): void
    {
        /** @var TrainerDexRepository $repo */
        $repo = static::getContainer()->get(TrainerDexRepository::class);

        $this->assertEquals(12, $this->getTrainerDexCount());

        $repo->set(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'homepogopokeball',
            new TrainerDexAttributes([])
        );

        $this->assertEquals(12, $this->getTrainerDexCount());
    }
}
