<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class CollectionAvailability
{
    use BaseEntityTrait;

    #[ORM\Column]
    public string $pokemonSlug;

    #[ORM\ManyToOne(targetEntity: Collection::class)]
    #[ORM\JoinColumn(nullable: false)]
    public Collection $collection;

    #[ORM\Column]
    #[Assert\NotBlank]
    public string $availability = '';
}
