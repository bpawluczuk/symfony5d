<?php

declare(strict_types=1);

namespace App\Location\Infrastructure\Repository;

use App\Location\Domain\Entity\Location;
use App\Location\Domain\Repository\LocationRepositoryInterface;
use App\Location\Domain\Value\LocationId;

class LocationRepository implements LocationRepositoryInterface
{
    public function getById(LocationId $locationId): ?Location
    {
        return new Location(
            $locationId,
            'Test location'
        );
    }

    public function save(Location $location): void
    {

    }
}