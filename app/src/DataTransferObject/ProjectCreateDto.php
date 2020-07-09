<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Validator\Constraints\DtoUniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @DtoUniqueEntity(
 *     entityClass="App\Entity\Project",
 *     fieldMapping={"projectId": "projectId"},
 *     message="project.id.unique"
 * )
 */
final class ProjectCreateDto implements DataTransferObjectInterface
{
    /**
     * @Assert\NotBlank(message="project.id.blank")
     * @Assert\Length(max="5", maxMessage="project.id.max")
     */
    public ?string $projectId = null;

    /**
     * @Assert\NotBlank(message="project.name.blank")
     * @Assert\Length(max="128", maxMessage="project.name.max")
     */
    public ?string $name = null;
}
