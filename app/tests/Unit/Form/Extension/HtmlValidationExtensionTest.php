<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Extension;

use App\Form\Extension\HtmlValidationExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/** @covers \App\Form\Extension\HtmlValidationExtension */
final class HtmlValidationExtensionTest extends TestCase
{
    /** @covers \App\Form\Extension\HtmlValidationExtension::getExtendedTypes */
    public function testGetExtendedTypes(): void
    {
        $extendedTypes = HtmlValidationExtension::getExtendedTypes();

        self::assertSame([FormType::class], $extendedTypes);
    }

    /** @covers \App\Form\Extension\HtmlValidationExtension::buildView */
    public function testBuildViewWithValidation(): void
    {
        $htmlValidationExtension = new HtmlValidationExtension(true);

        $view = $this->prophesize(FormView::class);
        $form = $this->prophesize(FormInterface::class);

        $htmlValidationExtension->buildView(
            $view->reveal(),
            $form->reveal(),
            [],
        );

        self::assertNotTrue(array_key_exists('novalidate', $view->vars['attr']));
    }

    /** @covers \App\Form\Extension\HtmlValidationExtension::buildView */
    public function testBuildViewWithoutValidation(): void
    {
        $htmlValidationExtension = new HtmlValidationExtension(false);

        $view = $this->prophesize(FormView::class);
        $form = $this->prophesize(FormInterface::class);

        $htmlValidationExtension->buildView(
            $view->reveal(),
            $form->reveal(),
            [],
        );

        self::assertSame('novalidate', $view->vars['attr']['novalidate']);
    }
}
