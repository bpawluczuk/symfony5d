<?php

declare(strict_types=1);

namespace App\Auth\Application;

use App\User\Domain\Value\UserId;

class LogoutCommand
{
    private UserId $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}