<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints as Assert;

final class ProjectUpdateDto implements DataTransferObjectInterface
{
    /** @Assert\Length(max="128", maxMessage="project.name.max") */
    public ?string $name = null;
}
