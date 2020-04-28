<?php

declare(strict_types=1);

namespace App\Auth\Application;

use App\User\Domain\Value\PlainPassword;
use App\User\Domain\Value\Username;

class LoginCommand
{
    private Username $username;
    private PlainPassword $plainPassword;

    public function __construct(Username $username, PlainPassword $plainPassword)
    {
        $this->username = $username;
        $this->plainPassword = $plainPassword;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getPlainPassword(): PlainPassword
    {
        return $this->plainPassword;
    }
}