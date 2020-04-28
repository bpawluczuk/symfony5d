<?php

declare(strict_types=1);

namespace App\Tests\Task\Ui\Http;

use App\Project\Domain\Entity\Project;
use App\Project\Infrastructure\Repository\ProjectRepository;
use App\Task\Domain\Entity\Task;
use App\Task\Domain\Query\TaskReadModelInterface;
use App\Task\Infrastructure\Query\TaskReadModel;
use App\Task\Infrastructure\Query\TaskReadModelRepository;
use App\Task\Infrastructure\Repository\TaskRepository;
use App\Tests\ItseApiTestCase;
use App\User\Domain\Entity\User;
use App\User\Infrastructure\Repository\UserRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends ItseApiTestCase
{

    public function testAllTasksWillNotBeFetchedWhenNotFilteredByProject()
    {
        $this->assertResponseCode(
            $this->get('/api/tasks'),
            Response::HTTP_BAD_REQUEST,
            );
    }

    public function testAllTasksWillNotBeFetchedWhenNoTasksInTheProject()
    {
        $taskReadModelRepository = $this->createMock(TaskReadModelRepository::class);

        $taskReadModelRepository
            ->expects($this->once())
            ->method('getAllInProject')
            ->willReturn([]);

        self::$container->set(
            TaskReadModelRepository::class,
            $taskReadModelRepository,
            );

        $this->assertResponseCode(
            $this->get(sprintf('/api/tasks'), [
                'projectId' => Uuid::uuid4()->toString()
            ]),
            Response::HTTP_NOT_FOUND,
            );
    }

    public function testAllTasksAreFetched()
    {
        $taskReadModelRepository = $this->createMock(TaskReadModelRepository::class);

        $taskReadModelRepository
            ->expects($this->once())
            ->method('getAllInProject')
            ->willReturn([
                $this->createEmptyReadModel(),
                $this->createEmptyReadModel(),
                $this->createEmptyReadModel(),
            ]);

        self::$container->set(
            TaskReadModelRepository::class,
            $taskReadModelRepository,
            );

        $this->assertResponseCode(
            $this->get(sprintf('/api/tasks'), [
                'projectId' => Uuid::uuid4()->toString()
            ]),
            Response::HTTP_OK,
            );
    }

    public function testTaskWillNotBeFetchedWhenNotFilteredByProject()
    {
        $this->assertResponseCode(
            $this->get(sprintf('/api/tasks/%s', Uuid::uuid4()->toString())),
            Response::HTTP_BAD_REQUEST,
            );
    }

    public function testUserWillNotBeFetchedWhenDoesNotExist()
    {
        $taskReadModelRepository = $this->createMock(TaskReadModelRepository::class);

        $taskReadModelRepository
            ->expects($this->once())
            ->method('getByIdInProject')
            ->willReturn(null);

        self::$container->set(
            TaskReadModelRepository::class,
            $taskReadModelRepository,
            );

        $this->assertResponseCode(
            $this->get(sprintf('/api/tasks/%s', Uuid::uuid4()->toString()), [
                'projectId' => Uuid::uuid4()->toString()
            ]),
            Response::HTTP_NOT_FOUND,
            );
    }

    public function testTaskCanBeFetchedById()
    {
        $taskReadModelRepository = $this->createMock(TaskReadModelRepository::class);

        $taskReadModelRepository
            ->expects($this->once())
            ->method('getByIdInProject')
            ->willReturn($this->createEmptyReadModel());

        self::$container->set(
            TaskReadModelRepository::class,
            $taskReadModelRepository,
            );

        $this->assertResponse(
            $this->get(sprintf('/api/tasks/%s', Uuid::uuid4()->toString()), [
                'projectId' => Uuid::uuid4()->toString()
            ]),
            'task_by_id',
            );
    }

    public function testTaskWilNotBeCreatedWhenProjectDoesNotExist()
    {
        $projectRepository = $this->createMock(ProjectRepository::class);

        $projectRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn(null);

        self::$container->set(
            ProjectRepository::class,
            $projectRepository,
        );

        $this->assertResponseCode(
            $this->post(sprintf('/api/tasks/projects/%s', Uuid::uuid4()->toString()), [
                'name' => 'Example Task'
            ]),
            Response::HTTP_NOT_FOUND,
        );
    }

    public function testTaskWillNotBeCreatedWhenAlreadyExists()
    {
        $projectRepository = $this->createMock(ProjectRepository::class);
        $projectMock = $this->createMock(Project::class);

        $projectRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($projectMock);

        self::$container->set(
            ProjectRepository::class,
            $projectRepository,
        );

        $taskReadModelRepository = $this->createMock(TaskReadModelRepository::class);

        $taskReadModelRepository
            ->expects($this->once())
            ->method('getByNameInProject')
            ->willReturn($this->createEmptyReadModel());

        self::$container->set(
            TaskReadModelRepository::class,
            $taskReadModelRepository,
        );

        $this->assertResponseCode(
            $this->post(sprintf('/api/tasks/projects/%s', Uuid::uuid4()->toString()), [
                'name' => 'Example Task'
            ]),
            Response::HTTP_CONFLICT,
        );
    }

    public function testTaskWillBeCreatedInTheProject()
    {
        $projectRepository = $this->createMock(ProjectRepository::class);
        $projectMock = $this->createMock(Project::class);

        $projectRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($projectMock);

        self::$container->set(
            ProjectRepository::class,
            $projectRepository,
        );

        $taskReadModelRepository = $this->createMock(TaskReadModelRepository::class);

        $taskReadModelRepository
            ->expects($this->once())
            ->method('getByNameInProject')
            ->willReturn(null);

        self::$container->set(
            TaskReadModelRepository::class,
            $taskReadModelRepository,
        );

        $this->assertResponseCode(
            $this->post(sprintf('/api/tasks/projects/%s', Uuid::uuid4()->toString()), [
                'name' => 'Example Task'
            ]),
            Response::HTTP_CREATED,
        );
    }

    public function testFormErrorsWillBeReturnedWhenBadDataFormatDuringTaskCreation()
    {
        $this->assertResponseCode(
            $this->post(sprintf('/api/tasks/projects/%s', Uuid::uuid4()->toString()), [
                'bullshit' => 'data',
            ]),
            Response::HTTP_BAD_REQUEST,
            );
    }

    public function testTaskForWhichToBeAssignUserWillNotBeFetchedWhenDoesNotExist()
    {
        $taskRepository = $this->createMock(TaskRepository::class);

        $taskRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn(null);

        self::$container->set(
            TaskRepository::class,
            $taskRepository,
            );

        $this->assertResponseCode(
            $this->post(sprintf('/api/tasks/users/%s/%s', Uuid::uuid4()->toString(), Uuid::uuid4()->toString()), []),
            Response::HTTP_NOT_FOUND,
            );
    }

    public function testUserToBeAssignedToTheTaskWillNotBeFetchedWhenDoesNotExist()
    {
        $taskRepository = $this->createMock(TaskRepository::class);
        $taskMock = $this->createMock(Task::class);

        $taskRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($taskMock);

        self::$container->set(
            TaskRepository::class,
            $taskRepository,
            );

        $userRepository = $this->createMock(UserRepository::class);

        $userRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn(null);

        self::$container->set(
            UserRepository::class,
            $userRepository,
            );

        $this->assertResponseCode(
            $this->post(sprintf('/api/tasks/users/%s/%s', Uuid::uuid4()->toString(), Uuid::uuid4()->toString()), []),
            Response::HTTP_NOT_FOUND,
            );
    }

    public function testAssignmentUserToTaskWillNotBeCreatedWhenAlreadyExists()
    {
        $taskRepository = $this->createMock(TaskRepository::class);
        $taskMock = $this->createMock(Task::class);

        $taskRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($taskMock);

        self::$container->set(
            TaskRepository::class,
            $taskRepository,
            );

        $userRepository = $this->createMock(UserRepository::class);
        $userMock = $this->createMock(User::class);

        $userRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($userMock);

        self::$container->set(
            UserRepository::class,
            $userRepository,
            );

        $taskMock
            ->expects($this->once())
            ->method('hasUser')
            ->willReturn(true);

        $this->assertResponseCode(
            $this->post(sprintf('/api/tasks/users/%s/%s', Uuid::uuid4()->toString(), Uuid::uuid4()->toString()), []),
            Response::HTTP_CONFLICT,
            );
    }

    public function testAssignmentUserToTaskWillBeCreated()
    {
        $taskRepository = $this->createMock(TaskRepository::class);
        $taskMock = $this->createMock(Task::class);

        $taskRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($taskMock);

        self::$container->set(
            TaskRepository::class,
            $taskRepository,
            );

        $userRepository = $this->createMock(UserRepository::class);
        $userMock = $this->createMock(User::class);

        $userRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($userMock);

        self::$container->set(
            UserRepository::class,
            $userRepository,
            );

        $taskMock
            ->expects($this->once())
            ->method('hasUser')
            ->willReturn(false);

        $this->assertResponseCode(
            $this->post(sprintf('/api/tasks/users/%s/%s', Uuid::uuid4()->toString(), Uuid::uuid4()->toString()), []),
            Response::HTTP_CREATED,
            );
    }

    private function createEmptyReadModel(): TaskReadModelInterface
    {
        return new TaskReadModel(
            'id',
            'name'
        );
    }
}