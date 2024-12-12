<?php

namespace App\Message;

use App\Entity\Task;
use App\Entity\TaskNotification;
use Doctrine\ORM\EntityManager;

class TaskNotificationMessage
{
    public function __construct(
        public string $message,
        public string $notificationType,
        public int $taskId,
    ) {}
}