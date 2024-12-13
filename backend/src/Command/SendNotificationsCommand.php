<?php

namespace App\Command;

use App\Entity\TaskNotification;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use \App\Message\TaskNotificationMessage;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsCommand(name: 'app:send-notification')]
#[AsPeriodicTask('10 seconds', schedule: 'send_notification')]
final class SendNotificationsCommand extends Command
{
    protected static $defaultName = 'app:send-notification';
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;

    public function __construct(
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus,
        private LoggerInterface $logger)
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
        $now = new \DateTime();

        $notifications = $this->entityManager
            ->createQueryBuilder()
            ->select('n')
            ->from(TaskNotification::class, 'n')
            ->where('n.triggerTime <= :now')
            ->andWhere('n.isSent = false')
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();
        $this->logger->error($now->format('Y-m-d H:i:s') . ' - ' . count($notifications));
        foreach ($notifications as $notification) {
            $this->logger->error($notification->message);
            $this->bus->dispatch(new TaskNotificationMessage(
                $notification->message,
                $notification->notificationType,
                $notification->task->id,
            ));

            $notification->isSent = true;
            $this->entityManager->persist($notification);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}