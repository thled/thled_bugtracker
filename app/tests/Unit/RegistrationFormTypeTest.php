<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DataTransferObject\RegisterUserDto;
use App\Form\RegistrationFormType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** @covers \App\Form\RegistrationFormType */
final class RegistrationFormTypeTest extends TestCase
{
    private RegistrationFormType $registrationFormType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrationFormType = new RegistrationFormType();
    }

    /** @covers \App\Form\RegistrationFormType::buildForm */
    public function testBuildForm(): void
    {
        $builder = $this->prophesize(FormBuilderInterface::class);
        $builder->add(
            Argument::type('string'),
            Argument::type('string'),
            Argument::type('array'),
        )
            ->willReturn($builder)
            ->shouldBeCalledTimes(3);

        $this->registrationFormType->buildForm($builder->reveal(), []);
    }

    /** @covers \App\Form\RegistrationFormType::configureOptions */
    public function testConfigureOptions(): void
    {
        $resolver = $this->prophesize(OptionsResolver::class);
        $resolver->setDefault('data_class', RegisterUserDto::class)
            ->shouldBeCalledOnce();

        $this->registrationFormType->configureOptions($resolver->reveal());
    }
}
