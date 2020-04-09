<?php

declare(strict_types=1);

namespace App\Form;

use App\DataTransferObject\BugCreateDto;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BugCreateType extends BugBaseType
{
    /**
     * @param array<mixed> $options
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addBaseFields($builder);

        $builder
            ->add(
                'project',
                EntityType::class,
                [
                    'label' => 'bug.form.project',
                    'class' => Project::class,
                    'placeholder' => 'bug.form.project_placeholder',
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', BugCreateDto::class);
    }
}
