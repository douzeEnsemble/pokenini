<?php

declare(strict_types=1);

namespace App\Web\DTO;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class ElectionMetrics
{
    public int $maxView;
    public int $maxViewCount;
    public int $underMaxViewCount;
    public int $eloCount;

    /**
     * @param float[]|int[] $values
     */
    public function __construct(array $values = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options = $resolver->resolve($values);

        $this->maxView = $options['max_view'];
        $this->maxViewCount = $options['max_view_count'];
        $this->underMaxViewCount = $options['under_max_view_count'];
        $this->eloCount = $options['elo_count'];
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('max_view', 0);
        $resolver->setAllowedTypes('max_view', 'int');

        $resolver->setDefault('max_view_count', 0);
        $resolver->setAllowedTypes('max_view_count', 'int');

        $resolver->setDefault('under_max_view_count', 0);
        $resolver->setAllowedTypes('under_max_view_count', 'int');

        $resolver->setDefault('elo_count', 0);
        $resolver->setAllowedTypes('elo_count', 'int');
    }
}
