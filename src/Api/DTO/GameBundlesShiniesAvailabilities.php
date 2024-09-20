<?php

declare(strict_types=1);

namespace App\Api\DTO;

class GameBundlesShiniesAvailabilities
{
    /**
     * @param bool[] $gameBundlesShiniesAvailabilities
     */
    public function __construct(private array $gameBundlesShiniesAvailabilities) {}

    public function __get(string $bundle): ?bool
    {
        return $this->gameBundlesShiniesAvailabilities[$bundle] ?? null;
    }

    public function __isset(string $bundle): bool
    {
        return isset($this->gameBundlesShiniesAvailabilities[$bundle]);
    }

    public function __set(string $bundle, bool $value): void
    {
        throw new \Exception('Use constructor please');
    }

    /**
     * @return bool[]
     */
    public function all(): array
    {
        return $this->gameBundlesShiniesAvailabilities;
    }
}
