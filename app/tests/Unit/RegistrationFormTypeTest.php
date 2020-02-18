<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Form\RegistrationFormType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/** @covers \App\Form\RegistrationFormType */
class RegistrationFormTypeTest extends TestCase
{
    /** @covers \App\Form\RegistrationFormType::buildForm */
    public function testBuildForm(): void
    {
        $translator = $this->prophesize(TranslatorInterface::class);
        $registrationFormType = new RegistrationFormType($translator->reveal());

        $builder = $this->prophesize(FormBuilderInterface::class);
        $builder->add(
            Argument::type('string'),
            Argument::type('string'),
            Argument::type('array'),
        )
            ->willReturn($builder)
            ->shouldBeCalledTimes(3);

        $registrationFormType->buildForm($builder->reveal(), []);
    }
}
