<?php

namespace App\Tests\Api\Common\Traits;

trait PokemonListTrait
{
    /**
     * @param int[][]|string[][]|string[][][] $list
     * @param string[]                        $slugs
     */
    public function assertSameSlugs(array $list, array $slugs): void
    {
        $items = $list;
        array_walk($items, fn (&$item) => $item = $item['pokemon_slug']);

        $this->assertSame($items, $slugs);
    }
}
