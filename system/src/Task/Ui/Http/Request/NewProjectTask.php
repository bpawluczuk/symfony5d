<?php

declare(strict_types=1);

namespace App\Task\Ui\Http\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Swagger\Annotations as SWG;

class NewProjectTask
{
    /**
     * @SWG\Property(type="string", description="The unique name for the Task")
     * @NotBlank(message="Please provide name")
     */
    public $name;
}