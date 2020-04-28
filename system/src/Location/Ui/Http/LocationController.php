<?php

declare(strict_types=1);

namespace App\Location\Ui\Http;

use App\Shared\Ui\Http\RestController;
use App\Organization\Domain\Exception\OrganizationNotFoundException;
use App\Organization\Domain\Value\OrganizationId;
use App\Organization\Ui\Http\Request\NewOrganizationLocation;
use App\Location\Application\AddLocationToOrganizationCommand;
use App\Location\Domain\Exception\LocationAlreadyExistsException;
use App\Location\Domain\Exception\LocationNotFoundException;
use App\Location\Domain\Query\LocationReadModelRepositoryInterface;
use App\Location\Domain\Value\LocationId;
use App\Location\Ui\Http\Form\NewOrganizationLocationForm;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\SerializerInterface;
use App\Location\Infrastructure\Query\LocationReadModel;

class LocationController extends RestController
{
    private LocationReadModelRepositoryInterface $locationReadModelRepository;

    public function __construct(
        CommandBus $commandBus,
        SerializerInterface $serializer,
        LocationReadModelRepositoryInterface $locationReadModelRepository
    )
    {
        parent::__construct($commandBus, $serializer);
        $this->locationReadModelRepository = $locationReadModelRepository;
    }

    /**
     * @Route(
     *     "/api/locations/organizations/{organizationId}",
     *     requirements={"organizationId"="%uuid_regex%"},
     *     name="organization_location_add",
     *     methods={"POST"}
     * )
     *
     * @Operation(
     *     tags={"Locations"},
     *     summary="Add Location to Organization",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref=@Model(type=NewOrganizationLocation::class)),
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="New Location was created in this Organization",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid payload",
     *     ),
     * )
     */
    public function addLocationToOrganization(Request $request, string $organizationId): Response
    {
        $newOrganizationLocation = new NewOrganizationLocation();
        $this->handleForm($request, NewOrganizationLocationForm::class, $newOrganizationLocation);

        try {
            $this->commandBus->handle(new AddLocationToOrganizationCommand(
                OrganizationId::createFromString($organizationId)
            ));

            return $this->handleResource(null, Response::HTTP_CREATED);
        } catch (LocationAlreadyExistsException $e) {
            return $this->handleError($e, Response::HTTP_CONFLICT);
        } catch (OrganizationNotFoundException $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @Route(
     *     "/api/locations/{locationId}",
     *     name="location_get",
     *     requirements={"locationId"="%uuid_regex%"},
     *     methods={"GET"}
     * )
     *
     * @QueryParam(
     *     name="organizationId",
     *     requirements="%uuid_regex%",
     *     allowBlank=false,
     *     nullable=false,
     *     description="Location's Organization Id"
     * )
     *
     * @Operation(
     *     tags={"Locations"},
     *     summary="Get Location by its Id",
     *     @SWG\Response(
     *         response="200",
     *         description="Location",
     *         @Model(type=LocationReadModel::class)
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Location with given Id was not found"
     *     )
     * )
     */
    public function getById(string $locationId, string $organizationId): Response
    {
        try {
            $location = $this->locationReadModelRepository->getByIdInOrganization(
                LocationId::createFromString($locationId),
                OrganizationId::createFromString($organizationId)
            );

            if (!$location) {
                throw new LocationNotFoundException();
            }

            return $this->handleResource($location);
        } catch (LocationNotFoundException $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @Route(
     *     "/api/locations",
     *     name="location_get_all",
     *     methods={"GET"}
     * )
     *
     * @QueryParam(
     *     name="organizationId",
     *     requirements="%uuid_regex%",
     *     allowBlank=false,
     *     default="",
     *     description="Location's Organization Id"
     * )
     *
     * @Operation(
     *     tags={"Locations"},
     *     summary="Get all Locations",
     *     @SWG\Response(
     *         response="200",
     *         description="All Locations",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=LocationReadModel::class))
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="No Locations were found"
     *     )
     * )
     */
    public function getAll(string $organizationId): Response
    {
        try {
            $locations = $this->locationReadModelRepository->getAllInOrganization(
                OrganizationId::createFromString($organizationId)
            );

            if (!$locations) {
                throw new LocationNotFoundException();
            }

            return $this->handleResource($locations);
        } catch (LocationNotFoundException $e) {
            return $this->handleError($e);
        }
    }
}