<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\DataTransferObject\ProjectUpdateDto;
use App\Form\ProjectEditType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** @covers \App\Form\ProjectEditType */
final class ProjectEditTypeTest extends TestCase
{
    private ProjectEditType $projectEditType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectEditType = new ProjectEditType();
    }

    public function testBuildForm(): void
    {
        $builder = $this->prophesize(FormBuilderInterface::class);
        $builder
            ->add(
                Argument::type('string'),
                Argument::type('string'),
                Argument::type('array'),
            )
            ->willReturn($builder);

        $this->projectEditType->buildForm($builder->reveal(), []);

        $builder
            ->add(
                'name',
                Argument::type('string'),
                Argument::type('array'),
            )
            ->shouldBeCalledOnce();
    }

    public function testConfigureOptions(): void
    {
        $resolver = $this->prophesize(OptionsResolver::class);

        $this->projectEditType->configureOptions($resolver->reveal());

        $resolver
            ->setDefault(
                'data_class',
                ProjectUpdateDto::class,
            )
            ->shouldBeCalledOnce();
    }
}
