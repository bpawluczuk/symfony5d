<?php

declare(strict_types=1);

namespace App\Project\Domain\Factory;

use App\Project\Domain\Query\ProjectReadModelInterface;
use App\Project\Infrastructure\Query\ProjectReadModel;

class ProjectReadModelFactory
{
    public function create(string $id, string $name): ProjectReadModelInterface
    {
        return new ProjectReadModel($id, $name);
    }
}