<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\PresentableException;

class UserAlreadyExistsException extends PresentableException
{
    public $message = 'User already exists';
}