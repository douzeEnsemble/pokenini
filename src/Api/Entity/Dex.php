<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use App\Api\Entity\Traits\FrenchNamedTrait;
use App\Api\Entity\Traits\NamedTrait;
use App\Api\Entity\Traits\OrderedTrait;
use App\Api\Entity\Traits\SlugifiedTrait;
use App\Api\Entity\Traits\SoftDeleteable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Dex
{
    use BaseEntityTrait;
    use NamedTrait;
    use FrenchNamedTrait;
    use SlugifiedTrait;
    use OrderedTrait;
    use SoftDeleteable;

    #[ORM\Column(length: 13570)]
    public string $selectionRule = '';

    #[ORM\Column]
    public bool $isShiny = false;

    #[ORM\Column]
    public bool $isPremium = true;

    #[ORM\Column]
    public bool $isDisplayForm = true;

    #[ORM\Column(options: ['default' => 'box'])]
    public string $displayTemplate = 'box';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    public ?Region $region = null;

    #[ORM\Column(length: 655)]
    public string $description = '';

    #[ORM\Column(length: 655)]
    public string $frenchDescription = '';

    #[ORM\Column]
    public bool $isReleased = true;

    #[ORM\Column(options: ['default' => false])]
    public bool $canHoldElection = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public \DateTime $lastChangedAt;

    #[ORM\Column]
    public int $electionOrderNumber = 0;
}
