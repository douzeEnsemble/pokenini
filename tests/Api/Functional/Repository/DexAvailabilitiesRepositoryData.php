<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

class DexAvailabilitiesRepositoryData
{
    /**
     * @return int[][]|string[][][][]
     */
    public static function providerGetTotalTypesFilters(): array
    {
        return [
            'primary_type' => [
                'filters' => [
                    'primaryTypes' => [
                        'grass',
                    ],
                ],
                'expectedTotalCount' => 6,
            ],
            'primary_type_null' => [
                'filters' => [
                    'primaryTypes' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 1,
            ],
            'secondary_type' => [
                'filters' => [
                    'secondaryTypes' => [
                        'normal',
                    ],
                ],
                'expectedTotalCount' => 3,
            ],
            'secondary_type_null' => [
                'filters' => [
                    'secondaryTypes' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 9,
            ],
            'primary_and_secondary_types' => [
                'filters' => [
                    'primaryTypes' => [
                        'bug',
                    ],
                    'secondaryTypes' => [
                        'flying',
                    ],
                ],
                'expectedTotalCount' => 3,
            ],
            'any_type' => [
                'filters' => [
                    'anyTypes' => [
                        'normal',
                    ],
                ],
                'expectedTotalCount' => 7,
            ],
            'any_type_null' => [
                'filters' => [
                    'anyTypes' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 9,
            ],
        ];
    }

    /**
     * @return int[][]|string[][][][]
     */
    public static function providerGetTotalFormsFilters(): array
    {
        return [
            'category_form' => [
                'filters' => [
                    'categoryForms' => [
                        'starter',
                    ],
                ],
                'expectedTotalCount' => 2,
            ],
            'category_form_null' => [
                'filters' => [
                    'categoryForms' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 20,
            ],
            'regional_form' => [
                'filters' => [
                    'regionalForms' => [
                        'alolan',
                    ],
                ],
                'expectedTotalCount' => 3,
            ],
            'regional_form_null' => [
                'filters' => [
                    'regionalForms' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 19,
            ],
            'special_form' => [
                'filters' => [
                    'specialForms' => [
                        'gigantamax',
                    ],
                ],
                'expectedTotalCount' => 2,
            ],
            'special_form_null' => [
                'filters' => [
                    'specialForms' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 18,
            ],
            'special_forms' => [
                'filters' => [
                    'specialForms' => [
                        'gigantamax',
                        'mega',
                    ],
                ],
                'expectedTotalCount' => 3,
            ],
            'variant_form' => [
                'filters' => [
                    'variantForms' => [
                        'gender',
                    ],
                ],
                'expectedTotalCount' => 4,
            ],
            'variant_form_null' => [
                'filters' => [
                    'variantForms' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 18,
            ],
        ];
    }

    /**
     * @return int[][]|string[][][][]
     */
    public static function providerGetTotalCatchStatesFilters(): array
    {
        return [
            'catch_state' => [
                'filters' => [
                    'catchStates' => [
                        'maybe',
                    ],
                ],
                'expectedTotalCount' => 3,
            ],
            'catch_state_null' => [
                'filters' => [
                    'catchStates' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 1,
            ],
            'catch_states' => [
                'filters' => [
                    'catchStates' => [
                        'maybe',
                        'maybenot',
                    ],
                ],
                'expectedTotalCount' => 6,
            ],
        ];
    }

    /**
     * @return int[][]|string[][][][]
     */
    public static function providerGetTotalGamesFilters(): array
    {
        return [
            'original_game_bundles' => [
                'filters' => [
                    'originalGameBundles' => [
                        'ultrasunultramoon',
                    ],
                ],
                'expectedTotalCount' => 1,
            ],
            'original_game_bundles_null' => [
                'filters' => [
                    'originalGameBundles' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 0,
            ],
            'game_bundle_availabilities' => [
                'filters' => [
                    'gameBundleAvailabilities' => [
                        'ultrasunultramoon',
                    ],
                ],
                'expectedTotalCount' => 2,
            ],
            'game_bundle_availabilities_null' => [
                'filters' => [
                    'gameBundleAvailabilities' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 0,
            ],
            'game_bundle_shiny_availabilities' => [
                'filters' => [
                    'gameBundleShinyAvailabilities' => [
                        'ultrasunultramoon',
                    ],
                ],
                'expectedTotalCount' => 4,
            ],
            'game_bundle_shiny_availabilities_null' => [
                'filters' => [
                    'gameBundleShinyAvailabilities' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 0,
            ],
        ];
    }

    /**
     * @return int[][]|string[][][][]
     */
    public static function providerGetTotalFamiliesFilters(): array
    {
        return [
            'family' => [
                'filters' => [
                    'families' => [
                        'maybe',
                    ],
                ],
                'expectedTotalCount' => 3,
            ],
            'family_null' => [
                'filters' => [
                    'families' => [
                        'null',
                    ],
                ],
                'expectedTotalCount' => 1,
            ],
            'families' => [
                'filters' => [
                    'families' => [
                        'maybe',
                        'maybenot',
                    ],
                ],
                'expectedTotalCount' => 6,
            ],
        ];
    }

    /**
     * @return int[][]|string[][][][]
     */
    public static function providerGetTotalEmptyFilters(): array
    {
        return [
            'empty' => [
                'filters' => [
                    'primaryTypes' => [
                        '',
                    ],
                    'secondaryTypes' => [
                        '',
                    ],
                    'anyTypes' => [
                        '',
                    ],
                    'categoryForms' => [
                        '',
                    ],
                    'regionalForms' => [
                        '',
                    ],
                    'specialForms' => [
                        '',
                    ],
                    'variantForms' => [
                        '',
                    ],
                    'catchStates' => [
                        '',
                    ],
                    'originalGameBundles' => [
                        '',
                    ],
                    'gameBundleAvailabilities' => [
                        '',
                    ],
                    'gameBundleShinyAvailabilities' => [
                        '',
                    ],
                    'families' => [
                        '',
                    ],
                ],
                'expectedTotalCount' => 22,
            ],
        ];
    }
}
