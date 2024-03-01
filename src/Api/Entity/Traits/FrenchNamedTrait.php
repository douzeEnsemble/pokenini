<?php

declare(strict_types=1);

namespace App\Api\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait FrenchNamedTrait
{
    #[ORM\Column]
    public string $frenchName = '';
}
