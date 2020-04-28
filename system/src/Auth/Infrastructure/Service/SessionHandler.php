<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Service;

use App\Auth\Infrastructure\Value\SessionId;
use App\User\Domain\Value\UserId;
use Predis\Client;

class SessionHandler
{
    private Client $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function createSession(UserId $userId, SessionId $sessionId): void
    {
        $this->redis->hset(
            'sessions',
            $userId->toString(),
            $sessionId->toString()
        );
    }

    public function hasSession(UserId $userId): bool
    {
        return (bool) $this->redis->hget(
            'sessions',
            $userId->toString()
        );
    }

    public function destroySession(UserId $userId): void
    {
        $this->redis->hdel(
            'sessions',
            [$userId->toString()]
        );
    }
}