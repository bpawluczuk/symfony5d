<?php

declare(strict_types=1);

namespace App\Project\Ui\Http\Request;

use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

class NewProject
{
    /**
     * @Assert\NotBlank(message="not_blank")
     * @SWG\Property(
     *     type="string",
     * )
     */
    public $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}