<?php

declare(strict_types=1);

namespace App\Shared\Ui\Exception;

use App\Shared\Domain\Exception\PresentableException;
use Symfony\Component\Form\FormInterface;

class InvalidFormException extends PresentableException
{
    private FormInterface $form;

    public function __construct(FormInterface $form)
    {
        parent::__construct('', 0, null);

        $this->form = $form;
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }
}