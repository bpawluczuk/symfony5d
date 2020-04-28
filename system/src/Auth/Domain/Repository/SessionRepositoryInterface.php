<?php

declare(strict_types=1);

namespace App\Auth\Domain\Repository;

use App\Auth\Infrastructure\Value\SessionId;
use App\User\Domain\Value\UserId;

interface SessionRepositoryInterface
{
    public function createSessionForUser(UserId $userId, SessionId $sessionId): void;
    public function destroyUserSession(UserId $userId): void;
}