<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

final class UserRegisterDto
{
    /**
     * @Assert\NotBlank(message="register.email.not_blank")
     * @Assert\Length(max="180", maxMessage="register.email.max")
     * @Assert\Email(message="register.email.email")
     * @UniqueUser(message="register.email.unique")
     */
    public ?string $email = null;

    /**
     * @Assert\NotBlank(message="register.password.not_blank")
     * @Assert\Length(min="6", minMessage="register.password.length.min")
     * @Assert\Length(max="4096", maxMessage="register.password.length.max")
     */
    public ?string $plainPassword = null;

    /** @Assert\IsTrue(message="register.agree_terms.is_true") */
    public ?bool $agreeTerms = null;
}
