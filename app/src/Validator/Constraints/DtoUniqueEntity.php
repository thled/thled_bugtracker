<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Assert\UniqueEntity for DTOs by https://gist.github.com/webbertakken/569409670bfc7c079e276f79260105ed
 *
 * @Annotation
 */
final class DtoUniqueEntity extends Constraint
{
    public const NOT_UNIQUE_ERROR = 'e777db8d-3af0-41f6-8a73-55255375cdca';
    public $em;
    public $entityClass;
    public $errorPath;
    public array $fieldMapping = [];
    public bool $ignoreNull = true;
    public string $message = 'This value is already used.';
    public string $repositoryMethod = 'findBy';
    protected static $errorNames = [self::NOT_UNIQUE_ERROR => 'NOT_UNIQUE_ERROR'];

    public function getDefaultOption(): string
    {
        return 'entityClass';
    }

    /** @return array<int, string> */
    public function getRequiredOptions(): array
    {
        return ['fieldMapping', 'entityClass'];
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return DtoUniqueEntityValidator::class;
    }
}
