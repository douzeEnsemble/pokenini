<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use App\Api\Entity\Traits\NamedTrait;
use App\Api\Entity\Traits\SlugifiedTrait;
use App\Api\Entity\Traits\SoftDeleteable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class GameGeneration
{
    use BaseEntityTrait;
    use NamedTrait;
    use SlugifiedTrait;
    use SoftDeleteable;

    public function getNumber(): int
    {
        return (int) $this->name;
    }
}
