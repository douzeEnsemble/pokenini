<?php

declare(strict_types=1);

namespace App\Web\Exception;

class EmptyContentException extends \RuntimeException
{
    protected $message = 'Content cannot be empty';
}
