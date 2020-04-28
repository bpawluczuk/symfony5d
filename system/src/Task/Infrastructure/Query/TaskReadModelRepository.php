<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Query;

use App\Project\Domain\Value\ProjectId;
use App\Task\Domain\Factory\TaskReadModelFactory;
use App\Task\Domain\Query\TaskReadModelInterface;
use App\Task\Domain\Query\TaskReadModelRepositoryInterface;
use App\Task\Domain\Value\TaskId;

class TaskReadModelRepository implements TaskReadModelRepositoryInterface
{
    private TaskReadModelFactory $taskReadModelFactory ;

    public function __construct(TaskReadModelFactory $taskReadModelFactory)
    {
        $this->taskReadModelFactory = $taskReadModelFactory;
    }

    public function getAllInProject(ProjectId $projectId): array
    {
        return [
            new TaskReadModel("UUID1", "Task 1"),
            new TaskReadModel("UUID2", "Task 2"),
            new TaskReadModel("UUID3", "Task 3"),
        ];
    }

    public function getByIdInProject(TaskId $taskId, ProjectId $projectId): ?TaskReadModelInterface
    {
        return null;
    }

    public function getByNameInProject(string $name, ProjectId $projectId) : ?TaskReadModelInterface
    {
        return null;
    }
}