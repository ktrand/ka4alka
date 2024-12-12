<?php

namespace App\Command;

use App\Entity\TaskNotification;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use \App\Message\TaskNotificationMessage;

class SendNotificationsCommand extends Command
{
    protected static $defaultName = 'app:send-notifications';
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, private LoggerInterface $logger)
    {
        parent::__construct(self::$defaultName);
        $this->entityManager = $entityManager;
        $this->bus = $bus;
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTimeImmutable();

        $nowWithoutSeconds = $now->setTime($now->format('H'), $now->format('i'));

        $output->writeln('Running the task every minute. - ' . $nowWithoutSeconds->format('Y-m-d H:i:s'));

        $notifications = $this->entityManager->getRepository(TaskNotification::class)
            ->findBy(['triggerTime' => $nowWithoutSeconds]);
        $forLogs = [];
        foreach ($notifications as $notification) {
            $forLogs[] = [
                'message' => $notification->message,
                'notificationType' => $notification->message,
                'task_id' => $notification->task->id,
            ];
            $this->bus->dispatch(new TaskNotificationMessage(
                $notification->message,
                $notification->notificationType,
                $notification->task->id,
            ));

            $notification->isSent = true;
            $this->entityManager->persist($notification);
        }
        $this->entityManager->flush();
        $this->logger->error('SendNotificationsCommand', $forLogs);
        return Command::SUCCESS;
    }
}