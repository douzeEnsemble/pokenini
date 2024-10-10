<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\Exception\EmptyContentException;
use App\Web\Exception\InvalidJsonException;
use App\Web\Exception\ModifyFailedException;
use App\Web\Service\GetTrainerPokedexService;
use App\Web\Service\ModifyTrainerDexService;
use App\Web\Service\RequestedContentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Json;

#[Route('/trainer')]
class TrainerUpsertController extends AbstractController
{
    public function __construct(
        private readonly GetTrainerPokedexService $getTrainerPokedexService,
        private readonly ModifyTrainerDexService $modifyTrainerDexService,
        private readonly RequestedContentService $requestedContentService,
    ) {}

    #[Route('/dex/{dexSlug}', methods: ['PUT'])]
    #[IsGranted('ROLE_TRAINER')]
    public function upsert(
        string $dexSlug,
    ): Response {
        try {
            $content = $this->requestedContentService->getContent(new Json());
        } catch (EmptyContentException|InvalidJsonException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        $pokedex = $this->getTrainerPokedexService->getPokedexData($dexSlug, []);
        if (null === $pokedex || empty($pokedex['dex'])) {
            return new JsonResponse([], 404);
        }

        $dex = $pokedex['dex'];

        if ($dex['is_premium'] && !$this->isGranted('ROLE_COLLECTOR')) {
            return new JsonResponse([], 404);
        }

        try {
            $this->modifyTrainerDexService->modifyDex(
                $dexSlug,
                $content,
            );
        } catch (ModifyFailedException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

        return new Response();
    }
}
