<?php

declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\Value\PlainPassword;
use App\User\Domain\Value\UserId;

class UpdateUserPasswordCommand
{
    private UserId $userId;
    private PlainPassword $oldPassword;
    private PlainPassword $newPassword;

    public function __construct(UserId $userId, PlainPassword $oldPassword, PlainPassword $newPassword)
    {
        $this->userId = $userId;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getOldPassword(): PlainPassword
    {
        return $this->oldPassword;
    }

    public function getNewPassword(): PlainPassword
    {
        return $this->newPassword;
    }
}