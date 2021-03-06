<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\DataTransferObject\UserRegisterDto;
use App\Form\RegistrationType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** @covers \App\Form\RegistrationType */
final class RegistrationTypeTest extends TestCase
{
    private RegistrationType $registrationType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrationType = new RegistrationType();
    }

    public function testBuildForm(): void
    {
        $builder = $this->prophesize(FormBuilderInterface::class);
        $builderAdd = $builder->add(
            Argument::type('string'),
            Argument::type('string'),
            Argument::type('array'),
        );
        $builderAdd->willReturn($builder);

        $this->registrationType->buildForm($builder->reveal(), []);

        $builderAdd->shouldBeCalledTimes(3);
    }

    public function testConfigureOptions(): void
    {
        $resolver = $this->prophesize(OptionsResolver::class);
        $resolverSetDefault = $resolver->setDefault(
            'data_class',
            UserRegisterDto::class,
        );

        $this->registrationType->configureOptions($resolver->reveal());

        $resolverSetDefault->shouldBeCalledOnce();
    }
}
