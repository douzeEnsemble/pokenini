<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use App\Api\Entity\Traits\FrenchNamedTrait;
use App\Api\Entity\Traits\NamedTrait;
use App\Api\Entity\Traits\SoftDeleteable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
#[ORM\Entity]
class Pokemon
{
    use BaseEntityTrait;
    use NamedTrait;
    use FrenchNamedTrait;
    use SoftDeleteable;

    #[ORM\Column(unique: true)]
    public string $slug;

    #[ORM\Column]
    public string $simplifiedName = '';

    #[ORM\Column]
    public string $simplifiedFrenchName = '';

    #[ORM\Column]
    public string $formsLabel = '';

    #[ORM\Column]
    public string $formsFrenchLabel = '';

    #[ORM\Column]
    public int $nationalDexNumber;

    #[ORM\Column]
    public string $family = '';

    #[ORM\Column]
    public bool $bankable = true;

    #[ORM\Column(nullable: true)]
    public ?bool $bankableish = null;

    #[ORM\ManyToOne(fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    public GameBundle $originalGameBundle;

    #[ORM\ManyToOne(fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    public ?VariantForm $variantForm;

    #[ORM\ManyToOne(fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    public ?RegionalForm $regionalForm;

    #[ORM\ManyToOne(fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    public ?SpecialForm $specialForm;

    #[ORM\ManyToOne(fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    public ?CategoryForm $categoryForm;

    #[ORM\Column]
    public string $iconName;

    #[ORM\Column]
    public int $familyOrder;

    #[ORM\ManyToOne(fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    public ?Type $primaryType;

    #[ORM\ManyToOne(fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    public ?Type $secondaryType;
}
