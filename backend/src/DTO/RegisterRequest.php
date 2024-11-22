<?php

namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest
{
    public function __construct(
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email,

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $password,

    #[Assert\NotBlank]
    #[Assert\EqualTo(propertyPath: 'password', message: 'Пароли должны совпадать')]
    public string $confirm_password,
    )
    {}
}