<?php

declare(strict_types=1);

namespace App\Web\DTO;

class ElectionVoteResult
{
    private function __construct(private readonly int $voteCount) {}

    /**
     * @param mixed[] $data
     */
    public static function createFromApi(array $data): self
    {
        return new self($data['voteCount']);
    }

    public function getVoteCount(): int
    {
        return $this->voteCount;
    }
}
