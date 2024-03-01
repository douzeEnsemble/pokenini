<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class RegionalDexNumber
{
    use BaseEntityTrait;

    #[ORM\Column]
    public string $pokemonSlug;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    public Region $region;

    #[ORM\Column]
    public int $dexNumber;
}
