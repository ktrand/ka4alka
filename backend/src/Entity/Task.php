<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    public string $title;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    public string $description;

    #[ORM\Column(type: 'boolean')]
    public bool $completed = false;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    public User $user;

    public function __construct(string $title, string $description, User $user, bool $completed = false)
    {
        $this->title = $title;
        $this->description = $description;
        $this->user = $user;
        $this->completed = $completed;
    }
}
