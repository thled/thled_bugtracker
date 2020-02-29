<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class HtmlValidationExtension extends AbstractTypeExtension
{
    private bool $htmlValidation;

    public function __construct(bool $htmlValidation)
    {
        $this->htmlValidation = $htmlValidation;
    }

    /** @return iterable<string> */
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    /**
     * @param array<mixed> $options
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $attr = $this->htmlValidation ? [] : ['novalidate' => 'novalidate'];
        $view->vars['attr'] = array_merge($view->vars['attr'], $attr);
    }
}
