<?php

declare(strict_types=1);

namespace App\Api\Service\Album;

use App\Api\DTO\AlbumFilter\AlbumFilters;
use App\Api\DTO\AlbumReport\Report;
use App\Api\DTO\AlbumReport\Statistic;
use App\Api\Repository\DexAvailabilitiesRepository;
use App\Api\Repository\PokedexRepository;

class AlbumReportService
{
    public function __construct(
        private readonly PokedexRepository $pokedexRepository,
        private readonly DexAvailabilitiesRepository $dexAvailabilitiesRepository,
    ) {}

    public function get(string $trainerExternalId, string $dexSlug, AlbumFilters $albumFilters): Report
    {
        $totalCaught = 0;
        $detail = [];

        $total = $this->dexAvailabilitiesRepository->getTotal(
            $trainerExternalId,
            $dexSlug,
            $albumFilters,
        );
        $totalUncaught = $total;

        $catchStatesCounts = $this->pokedexRepository->getCatchStatesCounts(
            $trainerExternalId,
            $dexSlug,
            $albumFilters,
        );

        foreach ($catchStatesCounts as $catchStatesCount) {
            $detail[] = new Statistic(
                (string) $catchStatesCount['slug'],
                (string) $catchStatesCount['name'],
                (string) $catchStatesCount['french_name'],
                (int) $catchStatesCount['count'],
            );

            if ('yes' === $catchStatesCount['slug']) {
                $totalCaught = (int) $catchStatesCount['count'];
            }

            if ('no' !== $catchStatesCount['slug']) {
                $totalUncaught -= (int) $catchStatesCount['count'];
            }
        }

        return new Report($total, $totalCaught, $totalUncaught, $detail);
    }
}
