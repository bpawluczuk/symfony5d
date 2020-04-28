<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\PresentableException;

class PasswordMismatchException extends PresentableException
{
    public $message = 'Provided passwords do not match';
}