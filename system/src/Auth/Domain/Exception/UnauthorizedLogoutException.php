<?php

declare(strict_types=1);

namespace App\Auth\Domain\Exception;

class UnauthorizedLogoutException extends AuthenticationException
{
    public $message = 'Unauthorized logout';
}