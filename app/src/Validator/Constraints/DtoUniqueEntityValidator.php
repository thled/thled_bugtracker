<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\DataTransferObject\DataTransferObjectInterface;
use Countable;
use DateTimeInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Iterator;
use IteratorAggregate;
use ReflectionClass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/** Assert\UniqueEntity for DTOs by https://gist.github.com/webbertakken/569409670bfc7c079e276f79260105ed */
final class DtoUniqueEntityValidator extends ConstraintValidator
{
    private DtoUniqueEntity $constraint;
    private $em;
    private ClassMetadata $entityMeta;
    private ManagerRegistry $registry;
    private $repository;
    private DataTransferObjectInterface $validationObject;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /** @param mixed $object */
    public function validate($object, Constraint $constraint): void
    {
        // Set arguments as class variables
        $this->validationObject = $object;
        $this->constraint = $constraint;
        $this->checkTypes();

        // Map types to criteria
        $this->entityMeta = $this->getEntityManager()->getClassMetadata($this->constraint->entityClass);
        $criteria = $this->getCriteria();
        // skip validation if there are no criteria (this can happen when the
        // "ignoreNull" option is enabled and fields to be checked are null
        if (count($criteria) === 0) {
            return;
        }

        $result = $this->checkConstraint($criteria);
        // If no entity matched the query criteria or a single entity matched,
        // which is the same as the entity being validated, the criteria is
        // unique.
        if (!$result || (count($result) === 1 && current($result) === $this->entityMeta)) {
            return;
        }

        // Property to which to return the violation
        $objectFields = array_keys($this->constraint->fieldMapping);
        $errorPath = $this->constraint->errorPath ?? $objectFields[0];

        // Value that caused the violation
        $invalidValue = $criteria[$this->constraint->fieldMapping[$errorPath]] ??
            $criteria[$this->constraint->fieldMapping[0]];

        // Build violation
        $this->context->buildViolation($this->constraint->message)
            ->atPath($errorPath)
            ->setParameter('{{ value }}', $this->formatWithIdentifiers($invalidValue))
            ->setInvalidValue($invalidValue)
            ->setCode(DtoUniqueEntity::NOT_UNIQUE_ERROR)
            ->setCause($result)
            ->addViolation();
    }

    private function checkTypes(): void
    {
        if (!$this->validationObject instanceof DataTransferObjectInterface) {
            throw new UnexpectedTypeException($this->validationObject, DataTransferObjectInterface::class);
        }

        if (!$this->constraint instanceof DtoUniqueEntity) {
            throw new UnexpectedTypeException($this->constraint, DtoUniqueEntity::class);
        }

        if ($this->constraint->entityClass === null || !class_exists($this->constraint->entityClass)) {
            throw new UnexpectedTypeException($this->constraint->entityClass, Entity::class);
        }

        if (!is_array($this->constraint->fieldMapping) || count($this->constraint->fieldMapping) === 0) {
            throw new UnexpectedTypeException($this->constraint->fieldMapping, '[objectProperty => entityProperty]');
        }

        if ($this->constraint->errorPath !== null && !is_string($this->constraint->errorPath)) {
            throw new UnexpectedTypeException($this->constraint->errorPath, 'string or null');
        }
    }

    private function getEntityManager(): ObjectManager
    {
        if ($this->em !== null) {
            return $this->em;
        }

        if ($this->constraint->em) {
            $this->em = $this->registry->getManager($this->constraint->em);

            if (!$this->em) {
                throw new ConstraintDefinitionException(sprintf(
                    'Object manager "%s" does not exist.',
                    $this->constraint->em,
                ));
            }
        } else {
            $this->em = $this->registry->getManagerForClass($this->constraint->entityClass);

            if (!$this->em) {
                throw new ConstraintDefinitionException(sprintf(
                    'Unable to find the object manager associated with an entity of class "%s".',
                    $this->constraint->entityClass,
                ));
            }
        }

        return $this->em;
    }

    /** @return array<string, string> */
    private function getCriteria(): array
    {
        $validationClass = new ReflectionClass($this->validationObject);

        $criteria = [];
        foreach ($this->constraint->fieldMapping as $objectField => $entityField) {
            // DTO Property (key) should exist on DataTransferObject
            if (!$validationClass->hasProperty($objectField)) {
                throw new ConstraintDefinitionException(sprintf(
                    'Property for fieldMapping key "%s" does not exist on this Object.',
                    $objectField,
                ));
            }

            // Entity Property (value) should exist in the Entity Class
            if (!property_exists($this->constraint->entityClass, $entityField)) {
                throw new ConstraintDefinitionException(sprintf(
                    'Property for fieldMapping key "%s" does not exist in given EntityClass.',
                    $objectField,
                ));
            }

            // Entity Property (value) should exist in the ORM
            if (!$this->entityMeta->hasField($entityField) && !$this->entityMeta->hasAssociation($entityField)) {
                throw new ConstraintDefinitionException(sprintf(
                    'The field "%s" is not mapped by Doctrine, so it cannot be validated for uniqueness.',
                    $entityField,
                ));
            }

            $fieldValue = $validationClass->getProperty($objectField)->getValue($this->validationObject);

            // validation doesn't fail if one of the fields is null and if null values should be ignored
            if ($fieldValue === null && !$this->constraint->ignoreNull) {
                throw new UniqueConstraintViolationException('Unique value can not be NULL');
            }

            $criteria[$entityField] = $fieldValue;

            if ($criteria[$entityField] === null || !$this->entityMeta->hasAssociation($entityField)) {
                continue;
            }

            /* Ensure the Proxy is initialized before using reflection to
             * read its identifiers. This is necessary because the wrapped
             * getter methods in the Proxy are being bypassed.
             */
            $this->getEntityManager()->initializeObject($criteria[$entityField]);
        }

        return $criteria;
    }

    /**
     * @param array<string, string> $criteria
     * @return array<int, string>
     */
    private function checkConstraint(array $criteria): array
    {
        $result = $this->getRepository()->{$this->constraint->repositoryMethod}($criteria);

        if ($result instanceof IteratorAggregate) {
            $result = $result->getIterator();
        }

        /* If the result is a MongoCursor, it must be advanced to the first
         * element. Rewinding should have no ill effect if $result is another
         * iterator implementation.
         */
        if ($result instanceof Iterator) {
            $result->rewind();
            if ($result instanceof Countable && 1 < count($result)) {
                $result = [$result->current(), $result->current()];
            } else {
                $result = $result->current();
                $result = $result === null ? [] : [$result];
            }
        } elseif (is_array($result)) {
            reset($result);
        } else {
            $result = $result === null ? [] : [$result];
        }

        return $result;
    }

    private function formatWithIdentifiers(string $value): string
    {
        if (!is_object($value) || $value instanceof DateTimeInterface) {
            return $this->formatValue($value, self::PRETTY_DATE);
        }

        $idClass = get_class($value);
        if ($this->entityMeta->getName() !== $idClass) {
            // non unique value might be a composite PK that consists of other entity objects
            $identifiers = $this->getEntityManager()->getMetadataFactory()->hasMetadataFor($idClass) ?
            $this->getEntityManager()->getClassMetadata($idClass)->getIdentifierValues($value) :
                [];
        } else {
            $identifiers = $this->entityMeta->getIdentifierValues($value);
        }

        if (!$identifiers) {
            return sprintf('object("%s")', $idClass);
        }

        array_walk(
            $identifiers,
            function (&$id, $field): void {
                $idAsString = !is_object($id) ||
                    $id instanceof DateTimeInterface ?
                    $this->formatValue($id, self::PRETTY_DATE) :
                    sprintf('object("%s")', get_class($id));

                $id = sprintf('%s => %s', $field, $idAsString);
            },
        );

        return sprintf(
            'object("%s") identified by (%s)',
            $idClass,
            implode(', ', $identifiers),
        );
    }

    private function getRepository(): ObjectRepository
    {
        if ($this->repository === null) {
            $this->repository = $this->getEntityManager()->getRepository($this->constraint->entityClass);
        }

        return $this->repository;
    }
}
