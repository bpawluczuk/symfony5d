<?php

declare(strict_types=1);

namespace App\Auth\Domain\Query;

use App\User\Domain\Value\UserId;

interface SessionReadModelRepositoryInterface
{
    public function getUserSession(UserId $userId): ?SessionReadModelInterface;
}