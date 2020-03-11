<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserDto
{
    /**
     * @Assert\NotBlank(message="user.email.not_blank")
     * @Assert\Length(max="180", maxMessage="user.email.max")
     * @Assert\Email(message="user.email.email")
     * @UniqueUser(message="user.email.unique")
     */
    public ?string $email = null;

    /**
     * @Assert\NotBlank(message="register.enter_password")
     * @Assert\Length(min="6", maxMessage="register.password_length.min")
     * @Assert\Length(max="4096", maxMessage="register.password_length.max")
     */
    public ?string $plainPassword = null;

    /** @Assert\IsTrue(message="register.should_agree") */
    public ?bool $agreeTerms = null;
}
