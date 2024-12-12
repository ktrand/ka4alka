<?php

namespace App\MessageHandler;

use App\Entity\Task;
use App\Message\TaskNotificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Factory\SenderFactory;

#[AsMessageHandler]
readonly class SendNotificationHandler
{

    public function __construct(
        private SenderFactory $senderFactory,
        private EntityManagerInterface $entityManager
    ) {}

    public function __invoke(TaskNotificationMessage $notificationMessage): void
    {
        $sender = $this->senderFactory->createSender($notificationMessage->notificationType);

        $sender->send(
            $this->getRecipient($notificationMessage),
            $this->getSubject($notificationMessage),
            $notificationMessage->message
        );
    }

    private function getSubject($notificationMessage): string
    {
        return "Task №{$notificationMessage->taskId} notification";
    }

    private function getRecipient(TaskNotificationMessage $notificationMessage): string
    {
        $task = $this->entityManager
            ->getRepository(Task::class)
            ->find($notificationMessage->taskId);

        if ($notificationMessage->notificationType === 'email') {
            return $task->user->getEmail();
        }
//        elseif ($notificationMessage->notificationType === 'sms') {
//            return $task->user->getPhoneNumber();
//        }

        throw new \InvalidArgumentException('Recipient type not found for this notification');
    }
}
