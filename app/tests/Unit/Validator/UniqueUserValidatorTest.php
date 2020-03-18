<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use App\Validator\UniqueUser;
use App\Validator\UniqueUserValidator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use stdClass;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/** @covers \App\Validator\UniqueUserValidator */
final class UniqueUserValidatorTest extends TestCase
{
    /** @covers \App\Validator\UniqueUserValidator::validate */
    public function testValidateAddViolation(): void
    {
        $value = 'foo@example.com';
        $constraint = $this->prophesize(UniqueUser::class);

        $userRepo = $this->mockUserRepo();

        $context = $this->prophesize(ExecutionContextInterface::class);
        $validator = $this->createValidatorWithContext($userRepo, $context);

        $this->assertAddViolation($context);

        $validator->validate($value, $constraint->reveal());
    }

    private function mockUserRepo(bool $returnUser = true): ObjectProphecy
    {
        $userRepo = $this->prophesize(UserRepositoryInterface::class);

        $user = $returnUser ? $this->prophesize(User::class)->reveal() : null;

        $userRepo->findOneBy(Argument::type('array'))->willReturn($user);

        return $userRepo;
    }

    /**
     * @param UserRepositoryInterface|ObjectProphecy $userRepo
     * @param ExecutionContextInterface|ObjectProphecy $context
     */
    private function createValidatorWithContext(
        ObjectProphecy $userRepo,
        ObjectProphecy $context
    ): UniqueUserValidator {
        $validator = new UniqueUserValidator($userRepo->reveal());
        $validator->initialize($context->reveal());

        return $validator;
    }

    private function assertAddViolation(ObjectProphecy $context): void
    {
        $constraintViolationBuilder = $this->prophesize(
            ConstraintViolationBuilderInterface::class,
        );
        $context->buildViolation(Argument::type('string'))
            ->shouldBeCalledOnce()
            ->willReturn($constraintViolationBuilder->reveal());
        $constraintViolationBuilder->addViolation()->shouldBeCalledOnce();
    }

    /**
     * @covers \App\Validator\UniqueUserValidator::validate
     * @dataProvider provideNoViolation
     * @param stdClass|string $value
     */
    public function testValidateNoViolation($value, bool $returnUser): void
    {
        $userRepo = $this->mockUserRepo($returnUser);

        $context = $this->prophesize(ExecutionContextInterface::class);
        $validator = $this->createValidatorWithContext($userRepo, $context);

        $this->assertNoViolation($context);

        $constraint = $this->prophesize(UniqueUser::class);
        $validator->validate($value, $constraint->reveal());
    }

    private function assertNoViolation(ObjectProphecy $context): void
    {
        $context->buildViolation(Argument::any())->shouldNotBeCalled();
    }

    /** @return array<array<stdClass|bool|string>> */
    public function provideNoViolation(): array
    {
        return [
            [new stdClass(), true],
            ['foo@example.com', false],
        ];
    }
}
