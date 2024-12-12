<?php

namespace App\Factory;

use App\Entity\TaskNotification;
use App\Interface\SenderInterface;
use App\Service\SMSSender;
use App\Service\EmailSender;

class SenderFactory
{
    private array $senders = [];

    public function __construct(
        private readonly SMSSender $smsSender,
        private readonly EmailSender $emailSender
    ) {
        $this->senders = [
            TaskNotification::NOTIFICATION_TYPE_EMAIL => $this->emailSender,
            TaskNotification::NOTIFICATION_TYPE_SMS => $this->smsSender
        ];
    }

    public function createSender(string $notificationType): SenderInterface
    {
        if (!isset($this->senders[$notificationType])) {
            throw new \InvalidArgumentException("Unsupported notification type: $notificationType");
        }

        return $this->senders[$notificationType];
    }
}
