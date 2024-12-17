<?php

namespace App\Message;

use DateTimeInterface;

class TaskNotificationMessage
{
    public function __construct(
        public string $message,
        public string $notificationType,
        public int $taskId,
        public DateTimeInterface $triggerTime
    ) {}
}