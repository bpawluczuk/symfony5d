<?php

declare(strict_types=1);

namespace App\Location\Domain\Factory;

use App\Location\Infrastructure\Query\LocationReadModel;
use App\Location\Domain\Query\LocationReadModelInterface;

class LocationReadModelFactory
{
    public function create(string $id): LocationReadModelInterface
    {
        return new LocationReadModel(
            $id,
        );
    }
}