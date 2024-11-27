<?php

namespace App\Message;

class TaskNotificationMessage
{
    public function __construct(
        public string $message,
        public string $notificationType,
        public int $taskId,
        public \DateTimeInterface $triggerTime
    ) {}
}