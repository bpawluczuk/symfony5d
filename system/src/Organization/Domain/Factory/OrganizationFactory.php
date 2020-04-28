<?php

declare(strict_types=1);

namespace App\Organization\Domain\Factory;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Value\OrganizationId;

class OrganizationFactory
{
    public function createOrganization(string $name, string $logoUrl = ''): Organization
    {
        return new Organization(
            OrganizationId::create(),
            $name,
            $logoUrl,
        );
    }
}