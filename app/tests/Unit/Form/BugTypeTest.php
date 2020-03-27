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
final class BugTypeTest extends TestCase
{
    private BugCreateType $bugType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bugType = new BugCreateType();
    }

    /** @covers \App\Form\BugCreateType::buildForm */
    public function testBuildForm(): void
    {
        $builder = $this->prophesize(FormBuilderInterface::class);
        $builder->add(
            Argument::type('string'),
            Argument::type('string'),
            Argument::type('array'),
        )
            ->willReturn($builder)
            ->shouldBeCalledTimes(11);

        $this->bugType->buildForm($builder->reveal(), []);
    }

    /** @covers \App\Form\BugCreateType::configureOptions */
    public function testConfigureOptions(): void
    {
        $resolver = $this->prophesize(OptionsResolver::class);
        $resolver->setDefault('data_class', BugCreateDto::class)
            ->shouldBeCalledOnce();

        $this->bugType->configureOptions($resolver->reveal());
    }
}
