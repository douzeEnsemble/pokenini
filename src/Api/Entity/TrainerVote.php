<?php

declare(strict_types=1);

namespace App\Api\Entity;

use App\Api\Entity\Traits\BaseEntityTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TrainerVote
{
    use BaseEntityTrait;

    #[ORM\Column]
    public string $trainerExternalId = '';

    #[ORM\Column]
    public string $electionSlug = '';

    #[ORM\Column(type: 'string')]
    public string $winnerSlug;

    /**
     * @var string[] $losers
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    public array $losers = [];

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
