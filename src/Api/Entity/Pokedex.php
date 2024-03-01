<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity]
#[UniqueConstraint(name: 'pokemon_dex_trainer', columns: ['pokemon_id', 'trainer_dex_id'])]
class Pokedex
{
    use BaseEntityTrait;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    public Pokemon $pokemon;

    // #[ORM\ManyToOne]
    // #[ORM\JoinColumn(nullable: false)]
    // public Dex $dex;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    public ?TrainerDex $trainerDex;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    public CatchState $catchState;

    // #[ORM\Column]
    // public string $trainerExternalId = '';
}
