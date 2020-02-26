<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Bug;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BugFormType extends AbstractType
{
    /**
     * @param array<mixed> $options
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bugId')
            ->add('status')
            ->add('project')
            ->add('priority')
            ->add('due')
            ->add('title')
            ->add('summary')
            ->add('reproduce')
            ->add('expected')
            ->add('actual')
            ->add(
                'reporter',
                EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => [$this, 'userToString'],
                    'disabled' => true,
                ],
            )
            ->add(
                'assignee',
                EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => [$this, 'userToString'],
                ],
            );
    }

    public function userToString(User $user): string
    {
        return $user->getEmail();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Bug::class,
            ],
        );
    }
}
