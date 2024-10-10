<?php

declare(strict_types=1);

namespace App\Web\Exception;

class InvalidJsonException extends \RuntimeException
{
    protected $message = 'Json is invalid';
}
