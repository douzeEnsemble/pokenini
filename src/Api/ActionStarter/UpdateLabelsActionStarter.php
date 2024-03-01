<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;
use App\Api\Message\UpdateLabels;

final class UpdateLabelsActionStarter extends AbstractActionStarter
{
    protected function getMessageClass(): string
    {
        return UpdateLabels::class;
    }

    protected function instanciate(string $identifier): ActionMessageInterface
    {
        return new UpdateLabels($identifier);
    }
}
