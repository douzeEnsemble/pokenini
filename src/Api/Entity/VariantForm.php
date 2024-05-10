<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use App\Api\Entity\Traits\NamedTrait;
use App\Api\Entity\Traits\FrenchNamedTrait;
use App\Api\Entity\Traits\OrderedTrait;
use App\Api\Entity\Traits\SlugifiedTrait;
use App\Api\Entity\Traits\SoftDeleteable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class VariantForm
{
    use BaseEntityTrait;
    use NamedTrait;
    use FrenchNamedTrait;
    use SlugifiedTrait;
    use OrderedTrait;
    use SoftDeleteable;
}
