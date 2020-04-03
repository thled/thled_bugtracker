<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\DataTransferObject\BugCreateDto;
use App\Form\BugCreateType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** @covers \App\Form\BugCreateType */
final class BugCreateTypeTest extends TestCase
{
    private BugCreateType $bugType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bugType = new BugCreateType();
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

        $this->bugType->buildForm($builder->reveal(), []);

        $builderAdd->shouldBeCalledTimes(10);
    }

    public function testConfigureOptions(): void
    {
        $resolver = $this->prophesize(OptionsResolver::class);
        $resolverSetDefault = $resolver->setDefault(
            'data_class',
            BugCreateDto::class,
        );

        $this->bugType->configureOptions($resolver->reveal());

        $resolverSetDefault->shouldBeCalledOnce();
    }
}
