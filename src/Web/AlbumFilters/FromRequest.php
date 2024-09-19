<?php

declare(strict_types=1);

namespace App\Web\AlbumFilters;

use Symfony\Component\HttpFoundation\Request;

class FromRequest
{
    private const STRING_FILTERS = [
        'cs',
        'f',
    ];

    private const MULTIPLE_FILTERS = [
        'fc',
        'fr',
        'fs',
        'fv',
        'at',
        't1',
        't2',
        'ogb',
        'gba',
        'gbsa',
    ];

    /**
     * @return string[]|string[][]
     */
    public static function get(Request $request): array
    {
        $filters = [];

        foreach (self::STRING_FILTERS as $filterName) {
            if ($request->query->has($filterName)) {
                $filters[$filterName] = $request->query->getString($filterName);
            }
        }

        foreach (self::MULTIPLE_FILTERS as $filterName) {
            if ($request->query->has($filterName)) {
                /** @var string[] $values */
                $values = $request->get($filterName, []);
                $filters[$filterName] = array_filter($values);
            }
        }

        return $filters;
    }
}
