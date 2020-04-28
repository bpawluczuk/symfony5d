<?php

declare(strict_types=1);

namespace App\Shared\Ui\Http;

use App\Shared\Ui\Exception\InvalidFormException;
use App\Shared\Domain\Exception\PresentableException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use League\Tactician\CommandBus;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class RestController extends AbstractFOSRestController
{
    protected CommandBus $commandBus;
    private SerializerInterface $serializer;

    public function __construct(CommandBus $commandBus, SerializerInterface $serializer)
    {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    protected function handleForm(Request $request, string $formType, object $data): FormInterface
    {
        $form = $this->createForm($formType, $data);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $form = $form->addError(new FormError('No payload in Request'));
            throw new InvalidFormException($form);
        }

        if (!$form->isValid()) {
            throw new InvalidFormException($form);
        }

        return $form;
    }

    public function handleResource($data = null, int $status = Response::HTTP_OK): Response
    {
        return new JsonResponse(
            $this->serializer->serialize($data, 'json'),
            $status,
            [],
            true
        );
    }

    public function handleError(PresentableException $exception, int $status = Response::HTTP_NOT_FOUND): Response
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
        ], $status);
    }
}