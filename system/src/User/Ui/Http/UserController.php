<?php

declare(strict_types=1);

namespace App\User\Ui\Http;

use App\Organization\Domain\Value\OrganizationId;
use App\Shared\Ui\Http\RestController;
use App\User\Application\UpdateUserPasswordCommand;
use App\User\Domain\Exception\PasswordMismatchException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Query\UserReadModelRepositoryInterface;
use App\User\Domain\Value\PlainPassword;
use App\User\Domain\Value\UserId;
use App\User\Ui\Http\Request\UpdateUserPassword;
use App\User\Infrastructure\Query\UserReadModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;

class UserController extends RestController
{
    /**
     * @Route(
     *     "/api/users/me",
     *     name="user_update",
     *     methods={"PUT"}
     * )
     *
     * @Operation(
     *     tags={"Users"},
     *     summary="Updates currenlty logged-in User's password. Old password must be present.",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref=@Model(type=UpdateUserPassword::class)),
     *     ),
     *     @SWG\Response(
     *         response="204",
     *         description="Password was updated successfuly.",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid payload",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Given User was not found",
     *     ),
     *     @SWG\Response(
     *         response="409",
     *         description="Old password does not match so password cannot be changed.",
     *     ),
     * )
     */
    public function updatePassword(Request $request, string $userId): Response
    {
        $updateUserPassword = new UpdateUserPassword();

        try {
            $this->commandBus->handle(new UpdateUserPasswordCommand(
                UserId::createFromString($userId),
                PlainPassword::create($updateUserPassword->oldPassword),
                PlainPassword::create($updateUserPassword->newPassword),
            ));

            return $this->handleResource(null, Response::HTTP_NO_CONTENT);
        } catch (UserNotFoundException $e) {
            return $this->handleError($e);
        } catch (PasswordMismatchException $e) {
            return $this->handleError($e, Response::HTTP_CONFLICT);
        }
    }

    /**
     * @Route(
     *     "/api/organizations/{organizationId}/users/{userId}",
     *     name="user_get",
     *     requirements={"organizationId"="%uuid_regex%", "userId"="%uuid_regex%"},
     *     methods={"GET"}
     * )
     *
     * @Operation(
     *     tags={"Users"},
     *     summary="Get User by its Id",
     *     @SWG\Response(
     *         response="200",
     *         description="User",
     *         @Model(type=UserReadModel::class)
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="User with given Id was not found"
     *     )
     * )
     */
    public function getById(string $userId, string $organizationId, UserReadModelRepositoryInterface $userReadModelRepository): Response
    {
        try {
            $user = $userReadModelRepository->getByIdInOrganization(
                UserId::createFromString($userId),
                OrganizationId::createFromString($organizationId),
            );

            if (!$user) {
                throw new UserNotFoundException();
            }

            return $this->handleResource($user);
        } catch (UserNotFoundException $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @Route(
     *     "/api/organizations/{organizationId}/users",
     *     name="user_get_all",
     *     requirements={"organizationId"="%uuid_regex%"},
     *     methods={"GET"}
     * )
     *
     * @Operation(
     *     tags={"Users"},
     *     summary="Get all Users",
     *     @SWG\Response(
     *         response="200",
     *         description="All Users",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=UserReadModel::class))
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="No Users were found"
     *     )
     * )
     */
    public function getAll(string $organizationId, UserReadModelRepositoryInterface $userReadModelRepository): Response
    {
        try {
            $users = $userReadModelRepository->getAllInOrganization(
                OrganizationId::createFromString($organizationId)
            );

            if (!$users) {
                throw new UserNotFoundException();
            }

            return $this->handleResource($users);
        } catch (UserNotFoundException $e) {
            return $this->handleError($e);
        }
    }
}