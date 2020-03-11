<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\DataTransferObject\RegisterUserDto;
use App\Form\RegistrationType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** @covers \App\Form\RegistrationType */
final class RegistrationTypeTest extends TestCase
{
    private RegistrationType $registrationFormType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrationFormType = new RegistrationType();
    }

    /** @covers \App\Form\RegistrationType::buildForm */
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

    /** @covers \App\Form\RegistrationType::configureOptions */
    public function testConfigureOptions(): void
    {
        $resolver = $this->prophesize(OptionsResolver::class);
        $resolver->setDefault('data_class', RegisterUserDto::class)
            ->shouldBeCalledOnce();

        $this->registrationFormType->configureOptions($resolver->reveal());
    }
}