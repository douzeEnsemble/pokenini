<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\DTO\DexFilters;
use App\Web\DTO\DexFiltersRequest;
use App\Web\Service\GetDexByRoleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/trainer')]
class TrainerIndexController extends AbstractController
{
    public function __construct(
        private readonly GetDexByRoleService $getDexByRoleService,
    ) {}

    #[Route('')]
    #[IsGranted('ROLE_TRAINER')]
    public function index(Request $request): Response
    {
        $trainerDex = $this->getDexByRoleService->getUserDex();

        $filters = DexFiltersRequest::dexFiltersFromRequest($request);

        $filteredTrainerDex = $this->filtersTrainerDex($trainerDex, $filters);

        return $this->render(
            'Trainer/index.html.twig',
            [
                'trainerDex' => $filteredTrainerDex,
                'filters' => $filters,
            ]
        );
    }

    /**
     * @param string[][] $trainerDex
     *
     * @return string[][]
     */
    private function filtersTrainerDex(array $trainerDex, DexFilters $filters): array
    {
        $dex = $trainerDex;

        if (null !== $filters->privacy->value) {
            $dex = array_filter(
                $dex,
                function ($item) use ($filters) {
                    return $filters->privacy->value == $item['is_private'];
                }
            );
        }

        if (null !== $filters->homepaged->value) {
            $dex = array_filter(
                $dex,
                function ($item) use ($filters) {
                    return $filters->homepaged->value == $item['is_on_home'];
                }
            );
        }

        if (null !== $filters->shiny->value) {
            $dex = array_filter(
                $dex,
                function ($item) use ($filters) {
                    return $filters->shiny->value == $item['is_shiny'];
                }
            );
        }

        if (null !== $filters->released->value) {
            $dex = array_filter(
                $dex,
                function ($item) use ($filters) {
                    return $filters->released->value == $item['is_released'];
                }
            );
        }

        if (null !== $filters->premium->value) {
            $dex = array_filter(
                $dex,
                function ($item) use ($filters) {
                    return $filters->premium->value == $item['is_premium'];
                }
            );
        }

        return $dex;
    }
}
