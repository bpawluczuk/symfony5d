<?php

declare(strict_types=1);

namespace App\Organization\Ui\Http;

use App\Organization\Application\AddUserToOrganizationCommand;
use App\Organization\Domain\Exception\OrganizationNotFoundException;
use App\Organization\Domain\Query\OrganizationReadModelRepositoryInterface;
use App\Organization\Domain\Value\OrganizationId;
use App\Organization\Ui\Http\Request\NewOrganizationUser;
use App\Shared\Ui\Http\RestController;
use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Value\PlainPassword;
use App\User\Domain\Value\UserId;
use App\Organization\Infrastructure\Query\OrganizationReadModel;
use App\User\Domain\Value\Username;
use App\Organization\Ui\Http\Form\NewOrganizationUserForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;

class OrganizationController extends RestController
{
    /**
     * @Route(
     *     "/api/organizations",
     *     name="organization_mine_get",
     *     methods={"GET"}
     * )
     *
     * @Operation(
     *     tags={"Organizations"},
     *     summary="Get Organizations of currently logged-in User",
     *     @SWG\Response(
     *         response="200",
     *         description="Organization",
     *         @Model(type=OrganizationReadModel::class)
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="User does not belong to any Organization",
     *     ),
     * )
     */
    public function getCurrentUserOrganization(OrganizationReadModelRepositoryInterface $organizationReadModelRepository): Response
    {
        $userId = UserId::create();

        try {
            $userOrganization = $organizationReadModelRepository->getUserOrganization($userId);

            if (!$userOrganization) {
                throw new OrganizationNotFoundException();
            }

            return $this->handleResource($userOrganization);
        } catch (OrganizationNotFoundException $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @Route(
     *     "/api/organizations/{organizationId}/users",
     *     requirements={"organizationId": "%uuid_regex%"},
     *     name="organization_user_add",
     *     methods={"POST"}
     * )
     *
     * @Operation(
     *     tags={"Users"},
     *     summary="Add User to Organization",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref=@Model(type=NewOrganizationUser::class)),
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="New User was created in this Organization",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid payload",
     *     ),
     *     @SWG\Response(
     *         response="409",
     *         description="User with a given username already exists",
     *     ),
     * )
     */
    public function addUserToOrganization(Request $request, string $organizationId): Response
    {
        $newOrganizationUser = new NewOrganizationUser();
        $this->handleForm($request, NewOrganizationUserForm::class, $newOrganizationUser);

        try {
            $this->commandBus->handle(new AddUserToOrganizationCommand(
                OrganizationId::createFromString($organizationId),
                Username::create($newOrganizationUser->username),
                PlainPassword::create($newOrganizationUser->password),
            ));

            return $this->handleResource(null, Response::HTTP_CREATED);
        } catch (UserAlreadyExistsException $e) {
            return $this->handleError($e, Response::HTTP_CONFLICT);
        } catch (OrganizationNotFoundException $e) {
            return $this->handleError($e);
        }
    }
}