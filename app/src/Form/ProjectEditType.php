<?php

declare(strict_types=1);

namespace App\Form;

use App\DataTransferObject\ProjectUpdateDto;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProjectEditType extends ProjectBaseType
{
    /**
     * @param array<mixed> $options
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addBaseFields($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', ProjectUpdateDto::class);
    }
}
