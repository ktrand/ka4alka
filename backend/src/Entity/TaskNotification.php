<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity]
class TaskNotification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public readonly int $id;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    public Task $task;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'string')]
    public string $message;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'datetime')]
    public \DateTimeInterface $triggerTime;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'string')]
    public string $notificationType;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    public bool $isSent = false;

    public function __construct()
    {}

    const NOTIFICATION_TYPES = [
        'sms' => 'SMS',
        'email' => 'Email',
    ];

    public static function getNotificationTypes(): array
    {
        return array_keys(self::NOTIFICATION_TYPES);
    }
}