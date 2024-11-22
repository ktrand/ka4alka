<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,

        #[Assert\NotBlank]
        public readonly string $password
    ) {}
} 