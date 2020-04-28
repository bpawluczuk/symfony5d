<?php

declare(strict_types=1);

namespace App\Task\Domain\Query;

use App\Project\Domain\Value\ProjectId;
use App\Task\Domain\Value\TaskId;

interface TaskReadModelRepositoryInterface
{
    public function getAllInProject(ProjectId $projectId): array;

    public function getByIdInProject(TaskId $taskId, ProjectId $projectId): ?TaskReadModelInterface;

    public function getByNameInProject(string $name, ProjectId $projectId): ?TaskReadModelInterface;
}