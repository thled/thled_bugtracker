<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\DataTransferObject\CreateBugDto;
use App\Form\BugType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** @covers \App\Form\BugType */
final class BugTypeTest extends TestCase
{
    private BugType $bugType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bugType = new BugType();
    }

    /** @covers \App\Form\BugType::buildForm */
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

    /** @covers \App\Form\BugType::configureOptions */
    public function testConfigureOptions(): void
    {
        $resolver = $this->prophesize(OptionsResolver::class);
        $resolver->setDefault('data_class', CreateBugDto::class)
            ->shouldBeCalledOnce();

        $this->bugType->configureOptions($resolver->reveal());
    }
}
