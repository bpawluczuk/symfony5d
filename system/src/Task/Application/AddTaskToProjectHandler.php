<?php

declare(strict_types=1);

namespace App\Task\Application;

use App\Project\Domain\Exception\ProjectNotFoundException;
use App\Project\Domain\Repository\ProjectRepositoryInterface;
use App\Shared\Application\HandlerInterface;
use App\Task\Domain\Exception\TaskAlreadyExistsException;
use App\Task\Domain\Factory\TaskFactory;
use App\Task\Domain\Query\TaskReadModelRepositoryInterface;

class AddTaskToProjectHandler implements HandlerInterface
{
    private ProjectRepositoryInterface $projectRepository;
    private TaskReadModelRepositoryInterface $taskReadModelRepository;
    private TaskFactory $taskFactory;

    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        TaskReadModelRepositoryInterface $taskReadModelRepository,
        TaskFactory $taskFactory
    )
    {
        $this->taskReadModelRepository = $taskReadModelRepository;
        $this->projectRepository = $projectRepository;
        $this->taskFactory = $taskFactory;
    }

    public function __invoke(AddTaskToProjectCommand $command)
    {
        $project = $this->projectRepository->getById($command->getProjectId());

        if (!$project) {
            throw new ProjectNotFoundException();
        }

        $existingTask = $this->taskReadModelRepository->getByNameInProject(
            $command->getName(),
            $command->getProjectId()
        );

        if ($existingTask) {
            throw new TaskAlreadyExistsException();
        }

        $newProjectTask = $this->taskFactory->createTask(
            $command->getProjectId(),
            $command->getName()
        );

        $project->addTask($newProjectTask);
        $this->projectRepository->save($project);
    }
}