<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

class AdminActionService extends AbstractApiService
{
    public function update(string $type): string
    {
        return $this->requestContent(
            'POST',
            "/istration/update/$type"
        );
    }

    public function calculate(string $type): string
    {
        return $this->requestContent(
            'POST',
            "/istration/calculate/$type"
        );
    }
}
