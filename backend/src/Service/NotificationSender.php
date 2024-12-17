<?php

// src/Service/NotificationSender.php
namespace App\Service;

use App\Message\TaskNotificationMessage;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class NotificationSender
{
    public function __construct(private MessageBusInterface $bus)
    {}

    /**
     * @throws ExceptionInterface
     */
    public function sendNotification(TaskNotificationMessage $notification): void
    {
        $delay = $notification->triggerTime->getTimestamp() - time();

        if ($delay > 0) {
            $this->bus->dispatch($notification, [
                'delivery_mode' => 2,
                'application_headers' => [
                    'x-delay' => $delay * 1000,
                ]
            ]);
        } else {
            $this->bus->dispatch($notification);
        }
    }
}
