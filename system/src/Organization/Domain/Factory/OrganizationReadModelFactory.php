<?php

declare(strict_types=1);

namespace App\Organization\Domain\Factory;

use App\Organization\Domain\Query\OrganizationReadModelInterface;
use App\Organization\Infrastructure\Query\OrganizationReadModel;

class OrganizationReadModelFactory
{
    public function create(string $id, string $name): OrganizationReadModelInterface
    {
        return new OrganizationReadModel(
            $id,
            $name,
        );
    }
}