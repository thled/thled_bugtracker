<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{
    private UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @param mixed $value
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!is_string($value)) {
            return;
        }

        if (!$constraint instanceof UniqueUser) {
            return;
        }

        $user = $this->userRepo->findOneBy(['email' => $value]);
        if (!$user instanceof User) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
