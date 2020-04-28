<?php

declare(strict_types=1);

namespace App\Location\Application;

use App\Organization\Domain\Value\OrganizationId;
use App\Location\Domain\Value\LocationId;

class AddLocationToOrganizationCommand
{
    private OrganizationId $organizationId;
    private LocationId $locationId;

    public function __construct(OrganizationId $organizationId)
    {
        $this->organizationId = $organizationId;
    }

    public function getOrganizationId(): OrganizationId
    {
        return $this->organizationId;
    }

    public function getLocationId(): LocationId
    {
        return $this->locationId;
    }

}