<?php

declare(strict_types=1);

namespace App\Task\Domain\Factory;

use App\Task\Domain\Query\TaskReadModelInterface;
use App\Task\Infrastructure\Query\TaskReadModel;

class TaskReadModelFactory
{
    public function create(string $id, string $name): TaskReadModelInterface
    {
        return new TaskReadModel(
            $id,
            $name
        );
    }
}