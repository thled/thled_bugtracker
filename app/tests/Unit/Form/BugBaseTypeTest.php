<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\Form\BugBaseType;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;

/** @covers \App\Form\BugBaseType */
final class BugBaseTypeTest extends TestCase
{
    public function testAddBaseFields(): void
    {
        $bugBaseTypeExt = new class extends BugBaseType {
            public function callAddBaseFields(FormBuilderInterface $builder): void
            {
                $this->addBaseFields($builder);
            }
        };

        $builder = $this->prophesize(FormBuilderInterface::class);
        $builderAdd = $builder->add(
            Argument::type('string'),
            Argument::type('string'),
            Argument::type('array'),
        );
        $builderAdd->willReturn($builder);

        $bugBaseTypeExt->callAddBaseFields($builder->reveal());

        $builderAdd->shouldBeCalledTimes(9);
    }

    // Cannot use it because PHPStan reports an error. (https://github.com/phpstan/phpstan/issues/3144)
//    private function createBugBaseTypeExt(): object
//    {
//        return new class extends BugBaseType {
//            public function callAddBaseFields(FormBuilderInterface $builder): void
//            {
//                $this->addBaseFields($builder);
//            }
//        };
//    }
}
