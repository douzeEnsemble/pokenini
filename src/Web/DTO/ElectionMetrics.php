<?php

declare(strict_types=1);

namespace App\Web\DTO;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class ElectionMetrics
{
    public float $avg;
    public float $stddev;
    public int $count;

    /**
     * @param string[]|string[][] $values
     */
    public function __construct(array $values = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options = $resolver->resolve($values);

        $this->avg = $options['avg_elo'];
        $this->stddev = $options['stddev_elo'];
        $this->count = $options['count_elo'];
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('avg_elo', '');
        $resolver->setAllowedTypes('avg_elo', ['float', 'int']);

        $resolver->setDefault('stddev_elo', '');
        $resolver->setAllowedTypes('stddev_elo', ['float', 'int']);

        $resolver->setDefault('count_elo', '');
        $resolver->setAllowedTypes('count_elo', 'int');
    }
}
