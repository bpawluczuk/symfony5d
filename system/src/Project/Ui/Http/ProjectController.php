<?php

declare(strict_types=1);

namespace App\Project\Ui\Http;

use App\Organization\Domain\Exception\OrganizationNotFoundException;
use App\Organization\Domain\Value\OrganizationId;
use App\Project\Application\AddProjectToOrganization;
use App\Project\Domain\Exception\ProjectAlreadyExistsException;
use App\Project\Domain\Exception\ProjectNotFoundException;
use App\Project\Domain\Query\ProjectReadModelRepositoryInterface;
use App\Project\Domain\Value\ProjectId;
use App\Project\Domain\Value\ProjectName;
use App\Project\Infrastructure\Query\ProjectReadModel;
use App\Project\Ui\Http\Form\NewProjectForm;
use App\Project\Ui\Http\Request\NewProject;
use App\Shared\Ui\Http\RestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use League\Tactician\CommandBus;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ProjectController extends RestController
{
    private ProjectReadModelRepositoryInterface $projectReadModelRepository;

    public function __construct(
        CommandBus $commandBus,
        SerializerInterface $serializer,
        ProjectReadModelRepositoryInterface $projectReadModelRepository
    )
    {
        parent::__construct($commandBus, $serializer);
        $this->projectReadModelRepository = $projectReadModelRepository;
    }

    /**
     * @Route("/api/projects/{projectId}", name="project_get", requirements={"projectId" : "%uuid_regex%"}, methods={"GET"})
     *
     * @Rest\QueryParam(
     *     name="organizationId",
     *     nullable=false,
     *     allowBlank=false,
     *     requirements="%uuid_regex%",
     *     description="Organization ID"
     * )
     *
     * @SWG\Parameter(
     *      name="projectId",
     *      in="path",
     *      type="string",
     *      required=true,
     *      description="Project ID",
     * ),
     *
     * @Operation(
     *     tags={"Projects"},
     *     summary="Get Project by ID in Organization",
     *     @SWG\Response(
     *         response="200",
     *         description="Returns fetched Project",
     *         @Model(type=ProjectReadModel::class)
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Project was not found"
     *     )
     * )
     */
    public function getByIdInOrganization(Request $request, string $projectId, string $organizationId): Response
    {
        try {
            $project = $this->projectReadModelRepository->getOneByIdInOrganization(
                ProjectId::createFromString($projectId),
                OrganizationId::createFromString($organizationId)
            );

            if (!$project) {
                throw new ProjectNotFoundException();
            }

            return $this->handleResource($project, Response::HTTP_OK);
        } catch (ProjectNotFoundException $exception) {
            return $this->handleError($exception, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/api/projects", name="project_get_all", methods={"GET"})
     *
     * @Rest\QueryParam(
     *     name="organizationId",
     *     nullable=false,
     *     allowBlank=false,
     *     requirements="%uuid_regex%",
     *     description="Organization ID"
     * ),
     *
     * @Operation(
     *     tags={"Projects"},
     *     summary="Get all Projects in Organization",
     *     @SWG\Response(
     *         response="200",
     *         description="Returns Project collection",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=ProjectReadModel::class))
     *         )
     *     )
     * )
     */
    public function getAllInOrganization(Request $request, string $organizationId): Response
    {
        $projects = $this->projectReadModelRepository->getAllInOrganization(
            OrganizationId::createFromString($organizationId)
        );

        return $this->handleResource($projects, Response::HTTP_OK);
    }

    /**
     * @Route("/api/projects", name="project_add", methods={"POST"})
     *
     *
     * @Rest\QueryParam(
     *     name="organizationId",
     *     nullable=false,
     *     allowBlank=false,
     *     requirements="%uuid_regex%",
     *     description="Organization ID"
     * ),
     * @SWG\Parameter(
     *     in="body",
     *     name="project",
     *     description="Project model",
     *     @SWG\Schema(
     *         ref=@Model(type=NewProject::class)
     *     )
     * ),
     *
     * @Operation(
     *     tags={"Projects"},
     *     summary="Create a Project in Organization",
     *     @SWG\Response(
     *         response="201",
     *         description="The project created",
     *         @Model(type=NewProject::class)
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid request data"
     *     ),
     *     @SWG\Response(
     *         response="409",
     *         description="Project with a given name already exists"
     *    )
     * )
     */
    public function addProjectToOrganization(Request $request, string $organizationId): Response
    {
        $project = new NewProject();
        $this->handleForm($request, NewProjectForm::class, $project);

        try {
            $this->commandBus->handle(new AddProjectToOrganization(
                OrganizationId::createFromString($organizationId),
                ProjectName::create($project->name)
            ));

            return $this->handleResource($project, Response::HTTP_CREATED);
        } catch (OrganizationNotFoundException $exception) {
            return $this->handleError($exception, Response::HTTP_BAD_REQUEST);
        } catch (ProjectAlreadyExistsException $exception) {
            return $this->handleError($exception, Response::HTTP_CONFLICT);
        }
    }
}