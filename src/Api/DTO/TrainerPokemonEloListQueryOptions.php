<?php

declare(strict_types=1);

namespace App\Api\DTO;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TrainerPokemonEloListQueryOptions
{
    public string $trainerExternalId;
    public string $dexSlug;
    public string $electionSlug;
    public int $count;

    /**
     * @param int[]|string[] $values
     */
    public function __construct(array $values = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options = $resolver->resolve($values);

        $this->trainerExternalId = $options['trainer_external_id'];
        $this->dexSlug = $options['dex_slug'];
        $this->electionSlug = $options['election_slug'];
        $this->count = $options['count'];
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('trainer_external_id');
        $resolver->setAllowedTypes('trainer_external_id', 'string');

        $resolver->setRequired('dex_slug');
        $resolver->setAllowedTypes('dex_slug', 'string');

        $resolver->setDefault('election_slug', '');
        $resolver->setAllowedTypes('election_slug', 'string');

        $resolver->setRequired('count');
        $resolver->setAllowedTypes('count', ['int', 'string']);
        $resolver->setNormalizer('count', function (Options $options, string $value): int {
            unset($options); // To remove PHPMD.UnusedFormalParameter warning

            return (int) $value;
        });
    }
}
