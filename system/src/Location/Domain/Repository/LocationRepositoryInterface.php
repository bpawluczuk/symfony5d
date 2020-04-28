<?php

declare(strict_types=1);

namespace App\Location\Domain\Repository;

use App\Location\Domain\Entity\Location;
use App\Location\Domain\Value\LocationId;

interface LocationRepositoryInterface
{
    public function getById(LocationId $locationId): ?Location;

    public function save(Location $location): void;
}