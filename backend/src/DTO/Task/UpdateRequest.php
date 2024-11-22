<?php

namespace App\DTO\Task;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 500)]
        public readonly string $title,

        #[Assert\NotBlank]
        public readonly string $description,

        public readonly bool $completed
    )
    {}
}