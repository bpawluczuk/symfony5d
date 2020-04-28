<?php

declare(strict_types=1);

namespace App\User\Domain\Factory;

use App\User\Domain\Entity\User;
use App\User\Domain\Value\PlainPassword;
use App\User\Domain\Value\UserId;
use App\User\Domain\Value\Username;

class UserFactory
{
    public function createUser(Username $username, PlainPassword $plainPassword): User
    {
        return new User(
            UserId::create(),
            $username->getUsername(),
            $plainPassword->getHashed(),
        );
    }
}