<?php

declare(strict_types=1);

namespace App\Form;

use App\DataTransferObject\ProjectCreateDto;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProjectCreateType extends ProjectBaseType
{
    /**
     * @param array<mixed> $options
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addBaseFields($builder);

        $builder->add(
            'projectId',
            TextType::class,
            ['label' => 'project.form.project_id'],
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', ProjectCreateDto::class);
    }
}
