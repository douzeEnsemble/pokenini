<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use App\Api\Entity\Traits\NamedTrait;
use App\Api\Entity\Traits\OrderedTrait;
use App\Api\Entity\Traits\SlugifiedTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
class Game
{
    use BaseEntityTrait;
    use NamedTrait;
    use SlugifiedTrait;
    use OrderedTrait;
    use SoftDeleteableEntity;

    #[ORM\ManyToOne(targetEntity: GameBundle::class)]
    #[ORM\JoinColumn(nullable: false)]
    public GameBundle $bundle;
}
