<?php

declare(strict_types=1);

namespace App\Web\Validator;

use Symfony\Component\Validator\Constraint;

class CatchStates extends Constraint
{
    public string $message = '"{{ string }}" is not a valid catch state';

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}
