<?php

namespace App\Web\Service;

use App\Web\Exception\ModifyFailedException;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\ModifyDexService;
use App\Web\Service\CacheInvalidator\AlbumCacheInvalidatorService;
use App\Web\Service\CacheInvalidator\DexCacheInvalidatorService;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ModifyTrainerDexService
{
    public function __construct(
        private readonly UserTokenService $userTokenService,
        private readonly ModifyDexService $modifyDexService,
        private readonly AlbumCacheInvalidatorService $albumCacheInvalidatorService,
        private readonly DexCacheInvalidatorService $dexCacheInvalidatorService,
    ) {}

    public function modifyDex(string $dexSlug, string $content): void
    {
        $trainerId = $this->userTokenService->getLoggedUserToken();

        try {
            $this->modifyDexService->modify(
                $dexSlug,
                $content,
                $trainerId
            );

            $this->albumCacheInvalidatorService->invalidate($dexSlug, $trainerId);
            $this->dexCacheInvalidatorService->invalidateByTrainerId($trainerId);
        } catch (HttpExceptionInterface|TransportExceptionInterface $e) {
            throw new ModifyFailedException();
        }
    }
}
