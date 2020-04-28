<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Ui\Exception\InvalidFormException;
use App\Shared\Ui\Exception\UnauthorizedActionCallException;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PresentableExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', 10],
            ]
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $e = $event->getThrowable();

        if ($e instanceof InvalidFormException) {
            $response = new JsonResponse([
                'errors' => $this->getFormErrors($e->getForm()),
            ], 400);

            $event->setResponse($response);
        }

        if ($e instanceof InvalidUuidStringException) {
            $response = new JsonResponse([
                'message' => $e->getMessage(),
            ], 400);

            $event->setResponse($response);
        }

        if ($e instanceof UnauthorizedActionCallException) {
            $response = new JsonResponse([
                'message' => 'This action needs authorized User',
            ], 401);

            $event->setResponse($response);
        }
    }

    private function getFormErrors(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getFormErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}