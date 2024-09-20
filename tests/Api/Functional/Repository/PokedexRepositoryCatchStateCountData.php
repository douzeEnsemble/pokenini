<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

class PokedexRepositoryCatchStateCountData
{
    /**
     * @return int[][][]|string[][][][]
     */
    public static function providerGetCatchStatesCountsTypesFilters(): array
    {
        return [
            'primary_type' => [
                [
                    'primaryTypes' => [
                        'grass',
                    ],
                ],
                [
                    6,
                    0,
                    0,
                    0,
                ],
            ],
            'primary_type_null' => [
                [
                    'primaryTypes' => [
                        'null',
                    ],
                ],
                [
                    1,
                    0,
                    0,
                    0,
                ],
            ],
            'secondary_type' => [
                [
                    'secondaryTypes' => [
                        'normal',
                    ],
                ],
                [
                    1,
                    0,
                    2,
                    0,
                ],
            ],
            'secondary_type_null' => [
                [
                    'secondaryTypes' => [
                        'null',
                    ],
                ],
                [
                    1,
                    3,
                    1,
                    4,
                ],
            ],
            'primary_and_secondary_types' => [
                [
                    'primaryTypes' => [
                        'bug',
                    ],
                    'secondaryTypes' => [
                        'flying',
                    ],
                ],
                [
                    1,
                    0,
                    0,
                    2,
                ],
            ],
            'any_types' => [
                [
                    'anyTypes' => [
                        'normal',
                    ],
                ],
                [
                    1,
                    2,
                    2,
                    2,
                ],
            ],
            'any_types_null' => [
                [
                    'anyTypes' => [
                        'null',
                    ],
                ],
                [
                    1,
                    3,
                    1,
                    4,
                ],
            ],
        ];
    }

    /**
     * @return int[][][]|string[][][][]
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public static function providerGetCatchStatesCountsFormsFilters(): array
    {
        return [
            'category_form' => [
                [
                    'categoryForms' => [
                        'starter',
                    ],
                ],
                [
                    1,
                    0,
                    0,
                    1,
                ],
            ],
            'category_form_null' => [
                [
                    'categoryForms' => [
                        'null',
                    ],
                ],
                [
                    8,
                    3,
                    3,
                    6,
                ],
            ],
            'regional_form' => [
                [
                    'regionalForms' => [
                        'alolan',
                    ],
                ],
                [
                    1,
                    0,
                    2,
                    0,
                ],
            ],
            'regional_form_null' => [
                [
                    'regionalForms' => [
                        'null',
                    ],
                ],
                [
                    8,
                    3,
                    1,
                    7,
                ],
            ],
            'special_form' => [
                [
                    'specialForms' => [
                        'gigantamax',
                    ],
                ],
                [
                    2,
                    0,
                    0,
                    0,
                ],
            ],
            'special_form_null' => [
                [
                    'specialForms' => [
                        'null',
                    ],
                ],
                [
                    5,
                    3,
                    3,
                    7,
                ],
            ],
            'special_forms' => [
                [
                    'specialForms' => [
                        'gigantamax',
                        'mega',
                    ],
                ],
                [
                    3,
                    0,
                    0,
                    0,
                ],
            ],
            'variant_form' => [
                [
                    'variantForms' => [
                        'gender',
                    ],
                ],
                [
                    1,
                    2,
                    0,
                    1,
                ],
            ],
            'variant_form_null' => [
                [
                    'variantForms' => [
                        'null',
                    ],
                ],
                [
                    8,
                    1,
                    3,
                    6,
                ],
            ],
        ];
    }

    /**
     * @return int[][][]|string[][][][]
     */
    public static function providerGetCatchStatesCountsCatchStatesFilters(): array
    {
        return [
            'catch_state' => [
                [
                    'catchStates' => [
                        'maybe',
                    ],
                ],
                [
                    0,
                    3,
                    0,
                    0,
                ],
            ],
            'catch_state_null' => [
                [
                    'catchStates' => [
                        'null',
                    ],
                ],
                [
                    1,
                    0,
                    0,
                    0,
                ],
            ],
            'catch_states' => [
                [
                    'catchStates' => [
                        'maybe',
                        'maybenot',
                    ],
                ],
                [
                    0,
                    3,
                    3,
                    0,
                ],
            ],
            'catch_state_negative' => [
                [
                    'catchStates' => [
                        '!maybe',
                    ],
                ],
                [
                    8,
                    0,
                    3,
                    7,
                ],
            ],
        ];
    }

    /**
     * @return int[][][]|string[][][][]
     */
    public static function providerGetCatchStatesCountsOriginalGamesFilters(): array
    {
        return [
            'original_game_bundle' => [
                [
                    'originalGameBundles' => [
                        'redgreenblueyellow',
                    ],
                ],
                [
                    4,
                    1,
                    1,
                    6,
                ],
            ],
            'original_game_bundle_null' => [
                [
                    'originalGameBundles' => [
                        'null',
                    ],
                ],
                [
                    0,
                    0,
                    0,
                    0,
                ],
            ],
        ];
    }

    /**
     * @return int[][][]|string[][][][]
     */
    public static function providerGetCatchStatesCountGamesBundlesFilters(): array
    {
        return [
            'game_bundle_availabilities' => [
                [
                    'gameBundleAvailabilities' => [
                        'ultrasunultramoon',
                    ],
                ],
                [
                    0,
                    0,
                    2,
                    0,
                ],
            ],
            'game_bundle_availabilities_null' => [
                [
                    'gameBundleAvailabilities' => [
                        'null',
                    ],
                ],
                [
                    0,
                    0,
                    0,
                    0,
                ],
            ],
            'game_bundle_availabilities_negative' => [
                [
                    'gameBundleAvailabilities' => [
                        '!ultrasunultramoon',
                    ],
                ],
                [
                    9,
                    3,
                    1,
                    7,
                ],
            ],
            'game_bundle_shiny_availabilities' => [
                [
                    'gameBundleShinyAvailabilities' => [
                        'ultrasunultramoon',
                    ],
                ],
                [
                    0,
                    2,
                    1,
                    1,
                ],
            ],
            'game_bundle_shiny_availabilities_null' => [
                [
                    'gameBundleShinyAvailabilities' => [
                        'null',
                    ],
                ],
                [
                    0,
                    0,
                    0,
                    0,
                ],
            ],
            'game_bundle_shiny_availabilities_negative' => [
                [
                    'gameBundleShinyAvailabilities' => [
                        '!ultrasunultramoon',
                    ],
                ],
                [
                    9,
                    1,
                    2,
                    6,
                ],
            ],
        ];
    }

    /**
     * @return int[][][]|string[][][][]
     */
    public static function providerGetFamiliesCountsCatchStatesFilters(): array
    {
        return [
            'family' => [
                [
                    'families' => [
                        'bulbasaur',
                    ],
                ],
                [
                    6,
                    0,
                    0,
                    0,
                ],
            ],
            'family_null' => [
                [
                    'families' => [
                        'null',
                    ],
                ],
                [
                    0,
                    0,
                    0,
                    0,
                ],
            ],
            'families' => [
                [
                    'families' => [
                        'bulbasaur',
                        'charmander',
                    ],
                ],
                [
                    6,
                    0,
                    0,
                    3,
                ],
            ],
        ];
    }
}
