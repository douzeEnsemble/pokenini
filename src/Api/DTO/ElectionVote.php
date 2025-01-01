<?php

declare(strict_types=1);

namespace App\Api\DTO;

use Symfony\Component\OptionsResolver\OptionsResolver;

final class ElectionVote
{
    public string $trainerExternalId;

    public string $electionSlug;

    public string $winnerSlug;

    /**
     * @var string[]
     */
    public array $losersSlugs;

    /**
     * @param string[]|string[][] $values
     */
    public function __construct(array $values = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $options = $resolver->resolve($values);

        $this->trainerExternalId = $options['trainer_external_id'];
        $this->electionSlug = $options['election_slug'];
        $this->winnerSlug = $options['winner_slug'];
        $this->losersSlugs = $options['losers_slugs'];
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('trainer_external_id');
        $resolver->setAllowedTypes('trainer_external_id', 'string');

        $resolver->setDefault('election_slug', '');
        $resolver->setAllowedTypes('election_slug', 'string');

        $resolver->setRequired('winner_slug');
        $resolver->setAllowedTypes('winner_slug', 'string');

        $resolver->setRequired('losers_slugs');
        $resolver->setAllowedTypes('losers_slugs', 'string[]');
    }
}
