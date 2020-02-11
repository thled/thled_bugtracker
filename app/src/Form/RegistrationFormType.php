<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

final class RegistrationFormType extends AbstractType
{
    private const PASSWORD_LENGTH_MIN = 6;
    private const PASSWORD_LENGTH_MAX = 4_096;

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param array<mixed> $options
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'register.email',
                ],
            )
            ->add(
                'agreeTerms',
                CheckboxType::class,
                [
                    'label' => 'register.agree_terms',
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue(
                            [
                                'message' => $this->translator->trans('register.should_agree'),
                            ],
                        ),
                    ],
                ],
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'label' => 'register.password',
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => $this->translator->trans('register.enter_password'),
                            ],
                        ),
                        new Length(
                            [
                                'min' => self::PASSWORD_LENGTH_MIN,
                                'minMessage' => $this->translator->trans('register.password_length'),
                                // max length allowed by Symfony for security reasons
                                'max' => self::PASSWORD_LENGTH_MAX,
                            ],
                        ),
                    ],
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ],
        );
    }
}
