<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\Form\ProjectBaseType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;

/** @covers \App\Form\ProjectBaseType */
final class ProjectBaseTypeTest extends TestCase
{
    public function testAddBaseFields(): void
    {
        $projectBaseTypeExt = new class extends ProjectBaseType
        {
            public function callAddBaseFields(FormBuilderInterface $builder): void
            {
                $this->addBaseFields($builder);
            }
        };

        $builder = $this->prophesize(FormBuilderInterface::class);
        $builder
            ->add(
                Argument::type('string'),
                Argument::type('string'),
                Argument::type('array'),
            )
            ->willReturn($builder);


        $projectBaseTypeExt->callAddBaseFields($builder->reveal());


        $builder
            ->add(
                'name',
                Argument::type('string'),
                Argument::type('array')
            )
            ->shouldBeCalledTimes(1);
    }
}
