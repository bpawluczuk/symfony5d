<?php

declare(strict_types=1);

namespace App\Auth\Domain\Exception;

class InvalidCredentialsException extends AuthenticationException
{
    public $message = 'Invalid Credentials';
}