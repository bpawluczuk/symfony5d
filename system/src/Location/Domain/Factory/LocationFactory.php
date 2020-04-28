<?php

declare(strict_types=1);

namespace App\Location\Domain\Factory;

use App\Location\Domain\Entity\Location;
use App\Location\Domain\Value\LocationId;
use App\Organization\Domain\Value\OrganizationId;

class LocationFactory
{
    public function createLocation(OrganizationId $organizationId): Location
    {
        return new Location(
            LocationId::create(),
            $organizationId,
        );
    }
}