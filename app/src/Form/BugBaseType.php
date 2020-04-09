<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class BugBaseType extends AbstractType
{
    protected function addBaseFields(FormBuilderInterface $builder): void
    {
        $builder
            ->add(
                'status',
                IntegerType::class,
                [
                    'label' => 'bug.form.status',
                ],
            )
            ->add(
                'priority',
                IntegerType::class,
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
                'assignee',
                EntityType::class,
                [
                    'label' => 'bug.form.assignee',
                    'class' => User::class,
                    'placeholder' => 'bug.form.assignee_placeholder',
                ],
            );
    }
}
