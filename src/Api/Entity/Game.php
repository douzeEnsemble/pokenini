<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use App\Api\Entity\Traits\NamedTrait;
use App\Api\Entity\Traits\OrderedTrait;
use App\Api\Entity\Traits\SlugifiedTrait;
use App\Api\Entity\Traits\SoftDeleteable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Game
{
    use BaseEntityTrait;
    use NamedTrait;
    use SlugifiedTrait;
    use OrderedTrait;
    use SoftDeleteable;

    #[ORM\ManyToOne(targetEntity: GameBundle::class)]
    #[ORM\JoinColumn(nullable: false)]
    public GameBundle $bundle;
}
