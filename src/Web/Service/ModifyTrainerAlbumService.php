<?php

namespace App\Web\Service;

use App\Web\Exception\ModifyFailedException;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\ModifyAlbumService;
use App\Web\Service\CacheInvalidator\AlbumsCacheInvalidatorService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ModifyTrainerAlbumService
{
    public function __construct(
        private readonly UserTokenService $userTokenService,
        private readonly ModifyAlbumService $modifyAlbumService,
        private readonly AlbumsCacheInvalidatorService $albumsCacheInvalidatorService,
        private readonly RequestStack $requestStack,
    ) {}

    public function modifyAlbum(
        string $dexSlug,
        string $pokemonSlug,
        string $content,
    ): void {
        $trainerId = $this->userTokenService->getLoggedUserToken();
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new ModifyFailedException();
        }

        try {
            $this->modifyAlbumService->modify(
                $request->getMethod(),
                $dexSlug,
                $pokemonSlug,
                $content,
                $trainerId
            );

            $this->albumsCacheInvalidatorService->invalidate();
        } catch (HttpExceptionInterface|TransportExceptionInterface $e) {
            throw new ModifyFailedException();
        }
    }
}
