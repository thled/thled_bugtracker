<?php

declare(strict_types=1);

namespace App\Form;

use App\DataTransferObject\CreateBugData;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BugType extends AbstractType
{
    /**
     * @param array<mixed> $options
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'status',
                TextType::class,
                [
                    'label' => 'bug.form.status',
                ],
            )
            ->add(
                'project',
                EntityType::class,
                [
                    'label' => 'bug.form.project',
                    'class' => Project::class,
                    'placeholder' => 'bug.form.project_placeholder',
                ],
            )
            ->add(
                'priority',
                TextType::class,
                [
                    'label' => 'bug.form.priority',
                ],
            )
            ->add(
                'due',
                DateType::class,
                [
                    'label' => 'bug.form.due',
                    'input' => 'datetime_immutable',
                    'placeholder' => [
                        'year' => 'bug.form.due_placeholder_year',
                        'month' => 'bug.form.due_placeholder_month',
                        'day' => 'bug.form.due_placeholder_day',
                    ],
                    'years' => range(date('Y'), date('Y', strtotime('+5 years'))),
                ],
            )
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'bug.form.title',
                ],
            )
            ->add(
                'summary',
                TextareaType::class,
                [
                    'label' => 'bug.form.summary',
                ],
            )
            ->add(
                'reproduce',
                TextareaType::class,
                [
                    'label' => 'bug.form.reproduce',
                ],
            )
            ->add(
                'expected',
                TextareaType::class,
                [
                    'label' => 'bug.form.expected',
                ],
            )
            ->add(
                'actual',
                TextareaType::class,
                [
                    'label' => 'bug.form.actual',
                ],
            )
            ->add(
                'reporter',
                EntityType::class,
                [
                    'label' => 'bug.form.reporter',
                    'class' => User::class,
                    'disabled' => true,
                ],
            )
            ->add(
                'assignee',
                EntityType::class,
                [
                    'label' => 'bug.form.assignee',
                    'class' => User::class,
                    'placeholder' => 'bug.form.assignee_placeholder',
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', CreateBugData::class);
    }
}
