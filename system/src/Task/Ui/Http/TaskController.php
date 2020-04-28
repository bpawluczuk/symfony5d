<?php

declare(strict_types=1);

namespace App\Task\Ui\Http;

use App\Project\Domain\Exception\ProjectNotFoundException;
use App\Project\Domain\Value\ProjectId;
use App\Shared\Ui\Http\RestController;
use App\Task\Application\AddTaskToProjectCommand;
use App\Task\Application\AssignUserToTaskCommand;
use App\Task\Application\CreateTaskCommand;
use App\Task\Domain\Exception\AssignmentUserWithTaskAlreadyExistsException;
use App\Task\Domain\Exception\TaskAlreadyExistsException;
use App\Task\Domain\Exception\TaskNotFoundException;
use App\Task\Domain\Query\TaskReadModelRepositoryInterface;
use App\Task\Infrastructure\Query\TaskReadModel;
use App\Task\Domain\Value\TaskId;
use App\Task\Ui\Http\Form\NewProjectTaskForm;
use App\Task\Ui\Http\Request\NewProjectTask;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Value\UserId;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\SerializerInterface;

class TaskController extends RestController
{
    private TaskReadModelRepositoryInterface $taskReadModelRepository;

    public function __construct(
        CommandBus $commandBus,
        SerializerInterface $serializer,
        TaskReadModelRepositoryInterface $taskReadModelRepository
    )
    {
        parent::__construct($commandBus, $serializer);
        $this->taskReadModelRepository = $taskReadModelRepository;
    }

    /**
     * @Route(
     *     "/api/tasks/{taskId}",
     *     name="task_get",
     *     requirements={"taskId"="%uuid_regex%"},
     *     methods={"GET"}
     * )
     *
     * @QueryParam(
     *     name="projectId",
     *     requirements="%uuid_regex%",
     *     allowBlank=false,
     *     nullable=false,
     *     description="Task's Project Id"
     * )
     *
     * @Operation(
     *     tags={"Tasks"},
     *     summary="Get Task by its Id",
     *     @SWG\Response(
     *         response="200",
     *         description="Task",
     *         @Model(type=TaskReadModel::class)
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Task with given Id was not found"
     *     )
     * )
     */
    public function getById(string $taskId, string $projectId): Response
    {
        try {
            $task = $this->taskReadModelRepository->getByIdInProject(
                TaskId::createFromString($taskId),
                ProjectId::createFromString($projectId)
            );

            if (!$task) {
                throw new TaskNotFoundException();
            }

            return $this->handleResource($task, Response::HTTP_OK);
        } catch (TaskNotFoundException $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @Route(
     *     "/api/tasks",
     *     name="tasks_get_all",
     *     methods={"GET"}
     *     )
     *
     * @QueryParam(
     *     name="projectId",
     *     requirements="%uuid_regex%",
     *     allowBlank=false,
     *     nullable=false,
     *     description="Task's Project Id"
     * )
     *
     * @Operation(
     *     tags={"Tasks"},
     *     summary="Get all Tasks",
     *     @SWG\Response(
     *         response="200",
     *         description="All Tasks",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=TaskReadModel::class))
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="No Tasks were found"
     *     )
     * )
     */
    public function getAll(string $projectId): Response
    {
        try {
            $tasks = $this->taskReadModelRepository->getAllInProject(
                ProjectId::createFromString($projectId)
            );

            if (!$tasks) {
                throw new TaskNotFoundException();
            }

            return $this->handleResource($tasks, Response::HTTP_OK);
        } catch (TaskNotFoundException $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @Route(
     *     "/api/tasks/projects/{projectId}",
     *     requirements={"projectId"="%uuid_regex%"},
     *     name="project_task_add",
     *     methods={"POST"}
     * )
     *
     * @Operation(
     *     tags={"Tasks"},
     *     summary="Add Task to Project",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref=@Model(type=NewProjectTask::class)),
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="New Task was created in this Project",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid payload",
     *     ),
     *     @SWG\Response(
     *         response="409",
     *         description="Task with a given name already exists",
     *     ),
     * )
     */
    public function addTaskToProject(Request $request, string $projectId): Response
    {
        $newProjectTask = new NewProjectTask();
        $this->handleForm($request, NewProjectTaskForm::class, $newProjectTask);

        try {
            $this->commandBus->handle(new AddTaskToProjectCommand(
                ProjectId::createFromString($projectId),
                $newProjectTask->name
            ));
            return $this->handleResource(null, Response::HTTP_CREATED);
        } catch (TaskAlreadyExistsException $e) {
            return $this->handleError($e, Response::HTTP_CONFLICT);
        } catch (ProjectNotFoundException $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @Route(
     *     "/api/tasks/users/{taskId}/{userId}",
     *     requirements={"taskId"="%uuid_regex%"},
     *     requirements={"userId"="%uuid_regex%"},
     *     name="user_task_add",
     *     methods={"POST"}
     * )
     *
     * @Operation(
     *     tags={"Tasks"},
     *     summary="Add User to Task",
     *     @SWG\Response(
     *         response="404",
     *         description="No Task were found or No User were found"
     *     ),
     *     @SWG\Response(
     *         response="409",
     *         description="Assignment User with Task already exists",
     *     ),
     * )
     */
    public function assignUserToTask(string $taskId, string $userId): Response
    {
        try {
            $this->commandBus->handle(new AssignUserToTaskCommand(
                TaskId::createFromString($taskId),
                UserId::createFromString($userId)
            ));
            return $this->handleResource(null, Response::HTTP_CREATED);
        } catch (TaskNotFoundException $e) {
            return $this->handleError($e, Response::HTTP_NOT_FOUND);
        } catch (UserNotFoundException $e) {
            return $this->handleError($e, Response::HTTP_NOT_FOUND);
        } catch (AssignmentUserWithTaskAlreadyExistsException $e) {
            return $this->handleError($e, Response::HTTP_CONFLICT);
        }
    }
}