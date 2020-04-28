<?php

declare(strict_types=1);

namespace App\Auth\Application;

use App\Auth\Infrastructure\Value\SessionId;
use App\Shared\Application\HandlerInterface;
use App\User\Domain\Value\Username;

class CreateUserSessionCommand implements HandlerInterface
{
    private SessionId $sessionId;
    private Username $username;

    public function __construct(SessionId $sessionId, Username $username)
    {
        $this->sessionId = $sessionId;
        $this->username = $username;
    }

    public function getSessionId(): SessionId
    {
        return $this->sessionId;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }
}