<?php

declare(strict_types=1);

namespace App\Web\DTO;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class ElectionMetrics
{
    public int $viewCountSum;
    public int $winCountSum;
    public int $dexTotalCount;

    public int $roundCount;
    public float $winnerAverage;
    public int $totalRoundCount;

    /**
     * @param float[]|int[] $values
     */
    public function __construct(array $values, int $perViewCount)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options = $resolver->resolve($values);

        $this->viewCountSum = $options['view_count_sum'];
        $this->winCountSum = $options['win_count_sum'];
        $this->dexTotalCount = $options['dex_total_count'];

        $this->roundCount = (int) round($this->viewCountSum / $perViewCount);
        $this->winnerAverage = 4.0;
        if (0 !== $this->roundCount) {
            $this->winnerAverage = round($this->winCountSum / $this->roundCount, 2);
        }

        $this->calculateTotalRoundCount($perViewCount);
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('view_count_sum', 0);
        $resolver->setAllowedTypes('view_count_sum', 'int');

        $resolver->setDefault('win_count_sum', 0);
        $resolver->setAllowedTypes('win_count_sum', 'int');

        $resolver->setDefault('dex_total_count', 0);
        $resolver->setAllowedTypes('dex_total_count', 'int');
    }

    private function calculateTotalRoundCount(int $perViewCount): void
    {
        $totalScreens = 0;
        $currentCount = $this->dexTotalCount;

        while ($currentCount > 0) {
            $screensInCurrentRound = round($currentCount / $perViewCount, 0);
            $totalScreens += $screensInCurrentRound;

            $currentCount = floor($currentCount / $this->winnerAverage);
        }

        $this->totalRoundCount = (int) $totalScreens;
    }
}
