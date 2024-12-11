<?php

namespace App\Command;

use App\Entity\TaskNotification;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use \App\Message\TaskNotificationMessage;

class SendNotificationsCommand extends Command
{
    protected static $defaultName = 'app:send-notifications';
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus)
    {
        parent::__construct(self::$defaultName);
        $this->entityManager = $entityManager;
        $this->bus = $bus;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTime();
        $output->writeln('Running the task every minute. - ' . $now->format('Y-m-d H:i:s'));

        $notifications = $this->entityManager->getRepository(TaskNotification::class)
            ->findBy(['triggerTime' => $now]);
        foreach ($notifications as $notification) {
            $this->bus->dispatch(new TaskNotificationMessage(
                $notification->message,
                $notification->notificationType,
                $notification->task->id,
                $notification->triggerTime
            ));

            $notification->isSent = true;
            $this->entityManager->persist($notification);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}