<?php

declare(strict_types=1);

namespace App\Form;

use App\DataTransferObject\UserShowDto;
use App\Enum\RoleEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserShowType extends AbstractType
{
    /**
     * @param array<mixed> $options
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'user.form.username',
                    'disabled' => true,
                ],
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'label' => 'user.form.roles',
                    'choices' => RoleEnum::toArray(),
                    'expanded' => true,
                    'multiple' => true,
                    'disabled' => true,
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', UserShowDto::class);
    }
}
