<?php

declare(strict_types=1);

namespace App\Organization\Application;

use App\Organization\Domain\Value\OrganizationId;
use App\User\Domain\Value\PlainPassword;
use App\User\Domain\Value\Username;

class AddUserToOrganizationCommand
{
    private OrganizationId $organizationId;
    private Username $username;
    private PlainPassword $password;

    public function __construct(OrganizationId $organizationId, Username $username, PlainPassword $password)
    {
        $this->organizationId = $organizationId;
        $this->username = $username;
        $this->password = $password;
    }

    public function getOrganizationId(): OrganizationId
    {
        return $this->organizationId;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getPassword(): PlainPassword
    {
        return $this->password;
    }
}