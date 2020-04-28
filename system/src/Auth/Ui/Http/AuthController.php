<?php

declare(strict_types=1);

namespace App\Auth\Ui\Http;

use App\Auth\Application\CreateUserSessionCommand;
use App\Auth\Application\LoginCommand;
use App\Auth\Application\LogoutCommand;
use App\Auth\Domain\Service\CurrentUserProvider;
use App\Auth\Domain\Exception\InvalidCredentialsException;
use App\Auth\Domain\Exception\UnauthorizedLogoutException;
use App\Auth\Infrastructure\Value\SessionId;
use App\Auth\Ui\Http\Form\LoginForm;
use App\Shared\Ui\Http\RestController;
use App\Auth\Ui\Http\Request\LoginCredentials;
use App\User\Domain\Value\PlainPassword;
use App\User\Domain\Value\UserId;
use App\User\Domain\Value\Username;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use App\Auth\Infrastructure\Query\SessionReadModel;

class AuthController extends RestController
{
    /**
     * @Route(
     *     "/api/login",
     *     name="user_login",
     *     methods={"POST"}
     * )
     *
     * @Operation(
     *     tags={"Security"},
     *     summary="Exchange username and password for Session Id",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref=@Model(type=LoginCredentials::class))
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="SessionId was created",
     *         @Model(type=SessionReadModel::class)
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid payload"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Bad Credentials"
     *     )
     * )
     */
    public function login(Request $request): Response
    {
        try {
            $credentials = new LoginCredentials();
            $this->handleForm($request, LoginForm::class, $credentials);

            $username = Username::create($credentials->username);

            $this->commandBus->handle(new LoginCommand(
                $username,
                PlainPassword::create($credentials->password),
            ));

            $sessionId = SessionId::create();
            $sessionReadModel = new SessionReadModel($sessionId->toString());

            $this->commandBus->handle(new CreateUserSessionCommand(
                $sessionId,
                $username,
            ));

            return $this->handleResource($sessionReadModel, Response::HTTP_CREATED);
        } catch (InvalidCredentialsException $e) {
            return $this->handleError($e, Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @Route(
     *     "/api/logout",
     *     name="user_logout",
     *     methods={"DELETE"}
     * )
     *
     * @Operation(
     *     tags={"Security"},
     *     summary="Logout current User",
     *     description="This will logout current User",
     *     @SWG\Response(
     *         response="204",
     *         description="User was successfully logged out"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="User has no active session"
     *     ),
     * )
     */
    public function logout(CurrentUserProvider $currentUserProvider): Response
    {
        try {
            $currentUser = $currentUserProvider->getCurrentUser();
            $userId = UserId::createFromString($currentUser->getId());

            $this->commandBus->handle(new LogoutCommand($userId));

            return $this->handleResource(null, Response::HTTP_NO_CONTENT);
        } catch (UnauthorizedLogoutException $e) {
            return $this->handleError($e, Response::HTTP_UNAUTHORIZED);
        }
    }
}