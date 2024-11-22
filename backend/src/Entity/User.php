<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public readonly int $id;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 255, unique: true)]
    private string $api_token;

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
        // Если храните временные, конфиденциальные данные
        // очистите их здесь
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->api_token;
    }

    public function setApiToken(string $api_token): self
    {
        $this->api_token = $api_token;
        return $this;
    }
}
