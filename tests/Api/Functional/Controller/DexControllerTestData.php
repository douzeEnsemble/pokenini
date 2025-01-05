<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

/**
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
final class DexControllerTestData
{
    /**
     * @return bool[][]|string[][]
     */
    public static function getUser12Content(): array
    {
        return [
            0 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo',
                'french_name' => 'Home PoGo',
                'slug' => 'home_pogo',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            1 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo OT',
                'french_name' => 'Home PoGo OT',
                'slug' => 'homepogoot',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            2 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo Poké Ball',
                'french_name' => 'Home PoGo Poké Ball',
                'slug' => 'homepogopokeball',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
        ];
    }

    /**
     * @return bool[][]|string[][]
     */
    public static function getUser12ContentWithUnreleased(): array
    {
        return [
            0 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo',
                'french_name' => 'Home PoGo',
                'slug' => 'home_pogo',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            1 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo OT',
                'french_name' => 'Home PoGo OT',
                'slug' => 'homepogoot',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            2 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo Poké Ball',
                'french_name' => 'Home PoGo Poké Ball',
                'slug' => 'homepogopokeball',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
        ];
    }

    /**
     * @return bool[][]|string[][]
     */
    public static function getUser12ContentWithPremium(): array
    {
        return [
            0 => [
                'dex_slug' => 'redgreenblueyellow',
                'name' => 'Red / Green / Blue / Yellow',
                'french_name' => 'Rouge / Vert / Bleu / Jaune',
                'slug' => 'redgreenblueyellow',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => false,
                'is_custom' => false,
            ],
            1 => [
                'dex_slug' => 'rubysapphireemerald',
                'name' => 'Ruby / Sapphire / Emerald',
                'french_name' => 'Rubis / Saphir / Émeraude',
                'slug' => 'rubysapphireemerald',
                'is_shiny' => false,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => false,
                'is_custom' => false,
            ],
            2 => [
                'dex_slug' => 'home',
                'name' => 'Home',
                'french_name' => 'Home',
                'slug' => 'home',
                'is_shiny' => false,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => false,
                'is_custom' => false,
            ],
            3 => [
                'dex_slug' => 'homeshiny',
                'name' => "Home\nShiny",
                'french_name' => "Home\nChromatique",
                'slug' => 'home_shiny',
                'is_shiny' => true,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            4 => [
                'dex_slug' => 'homeshiny',
                'name' => 'Home Shiny OT',
                'french_name' => 'Home Chromatique OT',
                'slug' => 'homeshinyot',
                'is_shiny' => true,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            5 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo',
                'french_name' => 'Home PoGo',
                'slug' => 'home_pogo',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            6 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo OT',
                'french_name' => 'Home PoGo OT',
                'slug' => 'homepogoot',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            7 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo Poké Ball',
                'french_name' => 'Home PoGo Poké Ball',
                'slug' => 'homepogopokeball',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            8 => [
                'dex_slug' => 'demo',
                'name' => 'Demo',
                'french_name' => 'Démo',
                'slug' => 'demo',
                'is_shiny' => false,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => false,
                'is_custom' => false,
            ],
            9 => [
                'dex_slug' => 'rubysapphireemeraldshiny',
                'name' => 'Ruby / Sapphire / Emerald: Shiny',
                'french_name' => 'Rubis / Saphir / Émeraude: Chromatique',
                'slug' => 'rubysapphireemeraldshiny',
                'is_shiny' => true,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => false,
                'is_custom' => false,
            ],
        ];
    }

    /**
     * @return bool[][]|string[][]
     */
    public static function getUser12ContentWithUnreleasedAndPremium(): array
    {
        return [
            0 => [
                'dex_slug' => 'redgreenblueyellow',
                'name' => 'Red / Green / Blue / Yellow',
                'french_name' => 'Rouge / Vert / Bleu / Jaune',
                'slug' => 'redgreenblueyellow',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => false,
                'is_custom' => false,
            ],
            1 => [
                'dex_slug' => 'goldsilvercrystal',
                'name' => 'Gold / Silver / Crystal',
                'french_name' => 'Or / Argent / Cristal',
                'slug' => 'goldsilvercrystal',
                'is_shiny' => false,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => false,
                'is_premium' => false,
                'is_custom' => false,
            ],
            2 => [
                'dex_slug' => 'rubysapphireemerald',
                'name' => 'Ruby / Sapphire / Emerald',
                'french_name' => 'Rubis / Saphir / Émeraude',
                'slug' => 'rubysapphireemerald',
                'is_shiny' => false,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => false,
                'is_custom' => false,
            ],
            3 => [
                'dex_slug' => 'home',
                'name' => 'Home',
                'french_name' => 'Home',
                'slug' => 'home',
                'is_shiny' => false,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => false,
                'is_custom' => false,
            ],
            4 => [
                'dex_slug' => 'homeshiny',
                'name' => "Home\nShiny",
                'french_name' => "Home\nChromatique",
                'slug' => 'home_shiny',
                'is_shiny' => true,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            5 => [
                'dex_slug' => 'homeshiny',
                'name' => 'Home Shiny OT',
                'french_name' => 'Home Chromatique OT',
                'slug' => 'homeshinyot',
                'is_shiny' => true,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            6 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo',
                'french_name' => 'Home PoGo',
                'slug' => 'home_pogo',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            7 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo OT',
                'french_name' => 'Home PoGo OT',
                'slug' => 'homepogoot',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            8 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo Poké Ball',
                'french_name' => 'Home PoGo Poké Ball',
                'slug' => 'homepogopokeball',
                'is_shiny' => false,
                'is_private' => false,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => true,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => true,
            ],
            9 => [
                'dex_slug' => 'demo',
                'name' => 'Demo',
                'french_name' => 'Démo',
                'slug' => 'demo',
                'is_shiny' => false,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => false,
                'is_custom' => false,
            ],
            10 => [
                'dex_slug' => 'rubysapphireemeraldshiny',
                'name' => 'Ruby / Sapphire / Emerald: Shiny',
                'french_name' => 'Rubis / Saphir / Émeraude: Chromatique',
                'slug' => 'rubysapphireemeraldshiny',
                'is_shiny' => true,
                'is_private' => true,
                'is_display_form' => true,
                'display_template' => 'box',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => false,
                'is_custom' => false,
            ],
        ];
    }

    /**
     * @return bool[][]|string[][]
     */
    public static function getUser13Content(): array
    {
        return [
            0 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo',
                'french_name' => 'Home PoGo',
                'slug' => 'homepogo',
                'is_shiny' => false,
                'is_private' => true,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => false,
            ],
        ];
    }

    /**
     * @return bool[][]|string[][]
     */
    public static function getUserUnknownContent(): array
    {
        return [
            0 => [
                'dex_slug' => 'homepogo',
                'name' => 'Home PoGo',
                'french_name' => 'Home PoGo',
                'slug' => 'homepogo',
                'is_shiny' => false,
                'is_private' => true,
                'is_display_form' => false,
                'display_template' => 'list-7',
                'is_on_home' => false,
                'is_released' => true,
                'is_premium' => true,
                'is_custom' => false,
            ],
        ];
    }
}
