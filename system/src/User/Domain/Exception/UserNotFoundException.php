<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\PresentableException;

class UserNotFoundException extends PresentableException
{
    public $message = 'User not found';
}