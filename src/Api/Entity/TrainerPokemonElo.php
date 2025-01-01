<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TrainerPokemonElo
{
    use BaseEntityTrait;

    #[ORM\Column]
    public int $elo = 1000;

    public function __construct(
        #[ORM\Column]
        private readonly string $trainerExternalId,
        #[ORM\ManyToOne(targetEntity: Pokemon::class)]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Pokemon $pokemon,
        #[ORM\Column]
        private readonly string $electionSlug = '',
    ) {}

    public function getTrainerExternalId(): string
    {
        return $this->trainerExternalId;
    }

    public function getPokemon(): Pokemon
    {
        return $this->pokemon;
    }

    public function getElectionSlug(): string
    {
        return $this->electionSlug;
    }
}
