<?php

namespace App\DTO\TaskNotification;

use App\Entity\TaskNotification;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $message,

        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        public readonly int $task_id,

        #[Assert\NotBlank]
        #[Assert\DateTime]
        public string $trigger_time,

        #[Assert\Choice([
            'callback' => [TaskNotification::class, 'getNotificationTypes'],
            'message' => 'Bad notification type'
        ])]        
        public readonly string $notification_type,
    )
    {}
}