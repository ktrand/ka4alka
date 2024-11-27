<?php

namespace App\Controller;

use App\DTO\TaskNotification\CreateRequest;
use App\DTO\TaskNotification\UpdateRequest;
use App\Entity\Task;
use App\Entity\TaskNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('api/notifications')]
#[IsGranted('ROLE_USER')]
class TaskNotificationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', methods: ['POST'])]
    public function addNotification(CreateRequest $request): JsonResponse
    {
        $task = $this->getTask($request->task_id);
        
        $this->checkAccess($task->user->id);

        $taskNotification = new TaskNotification();
        $this->setTaskNotificationProperties($taskNotification, $task, $request);

        $this->entityManager->persist($taskNotification);
        $this->entityManager->flush();

        return new JsonResponse(['task_notification' => $taskNotification]);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function editNotification(int $id, UpdateRequest $request): JsonResponse
    {
        $taskNotification = $this->getTaskNotification($id);
        
        $this->checkAccess($taskNotification->task->user->id);

        $taskNotification->task = $this->getTask($request->task_id);
        $this->setTaskNotificationProperties($taskNotification, $taskNotification->task, $request);

        $this->entityManager->flush();

        return new JsonResponse(['task_notification' => $taskNotification]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteNotification(int $id): JsonResponse
    {
        $taskNotification = $this->getTaskNotification($id);
                
        $this->checkAccess($taskNotification->task->user->id);

        $this->entityManager->remove($taskNotification);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Notification deleted successfully']);
    }

    private function checkAccess($ownerId): void
    {
        if ($ownerId !== $this->getUser()?->id) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }

    private function getTaskNotification(int $id): TaskNotification
    {
        $taskNotification = $this->entityManager
            ->getRepository(TaskNotification::class)
            ->findOneBy(['id' => $id]);

        if (!$taskNotification) {
            throw new NotFoundHttpException('Notification not found');
        }

        return $taskNotification;
    }

    private function getTask(int $id): Task
    {
        $task = $this->entityManager
        ->getRepository(Task::class)
        ->findOneBy(['id' => $id]);

        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }

        return $task;
    }

    private function setTaskNotificationProperties(TaskNotification $taskNotification, Task $task, $request): void
    {
        $taskNotification->task = $task;
        $taskNotification->message = $request->message;
        $taskNotification->triggerTime = $request->trigger_time;
        $taskNotification->notificationType = $request?->notification_type;
    }
}