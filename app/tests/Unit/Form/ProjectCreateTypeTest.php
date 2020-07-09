<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\DataTransferObject\ProjectCreateDto;
use App\Form\ProjectCreateType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** @covers \App\Form\ProjectCreateType */
final class ProjectCreateTypeTest extends TestCase
{
    private ProjectCreateType $projectCreateType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectCreateType = new ProjectCreateType();
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

        $this->projectCreateType->buildForm($builder->reveal(), []);

        $builder
            ->add(
                'projectId',
                Argument::type('string'),
                Argument::type('array')
            )
            ->shouldBeCalledOnce();
        $builder
            ->add(
                'name',
                Argument::type('string'),
                Argument::type('array')
            )
            ->shouldBeCalledOnce();
    }

    public function testConfigureOptions(): void
    {
        $resolver = $this->prophesize(OptionsResolver::class);

        $this->projectCreateType->configureOptions($resolver->reveal());

        $resolver
            ->setDefault(
                'data_class',
                ProjectCreateDto::class,
            )
            ->shouldBeCalledOnce();
    }
}
