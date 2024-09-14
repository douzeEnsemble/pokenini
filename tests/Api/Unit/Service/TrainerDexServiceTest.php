<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\DTO\DexQueryOptions;
use App\Api\DTO\TrainerDexAttributes;
use App\Api\Repository\TrainerDexRepository;
use App\Api\Service\TrainerDexService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(TrainerDexService::class)]
class TrainerDexServiceTest extends TestCase
{
    public function testInsertIfNeeded(): void
    {
        $repository = $this->createMock(TrainerDexRepository::class);
        $repository->expects($this->once())
            ->method('insertIfNeeded')
            ->with('7b52009b64fd0a2a49e6d8a939753077792b0554', 'bw2')
        ;

        $service = new TrainerDexService($repository);

        $service->insertIfNeeded('7b52009b64fd0a2a49e6d8a939753077792b0554', 'bw2');
    }

    public function testGetListQuery(): void
    {
        $queryOptions = new DexQueryOptions([
            'include_unreleased_dex' => false,
        ]);

        $repository = $this->createMock(TrainerDexRepository::class);
        $repository->expects($this->once())
            ->method('getListQuery')
            ->with('7b52009b64fd0a2a49e6d8a939753077792b0554', $queryOptions)
        ;

        $service = new TrainerDexService($repository);

        $service->getListQuery('7b52009b64fd0a2a49e6d8a939753077792b0554', $queryOptions);
    }

    public function testSet(): void
    {
        $attributes = new TrainerDexAttributes([
            'is_on_home' => false,
            'is_private' => true,
        ]);

        $repository = $this->createMock(TrainerDexRepository::class);
        $repository->expects($this->once())
            ->method('set')
            ->with('7b52009b64fd0a2a49e6d8a939753077792b0554', 'bw2', $attributes)
        ;

        $service = new TrainerDexService($repository);

        $service->set('7b52009b64fd0a2a49e6d8a939753077792b0554', 'bw2', $attributes);
    }
}
