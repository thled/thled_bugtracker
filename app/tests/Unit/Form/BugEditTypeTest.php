<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\DataTransferObject\BugUpdateDto;
use App\Form\BugEditType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** @covers \App\Form\BugEditType */
final class BugEditTypeTest extends TestCase
{
    private BugEditType $bugType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bugType = new BugEditType();
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

        $builderAdd->shouldBeCalledTimes(9);
    }

    public function testConfigureOptions(): void
    {
        $resolver = $this->prophesize(OptionsResolver::class);
        $resolverSetDefault = $resolver->setDefault(
            'data_class',
            BugUpdateDto::class,
        );

        $this->bugType->configureOptions($resolver->reveal());

        $resolverSetDefault->shouldBeCalledOnce();
    }
}
