<?php

namespace App\Controller;

use App\DTO\Task\CreateRequest;
use App\DTO\Task\UpdateRequest;
use App\Entity\Task;
use Doctrine\DBAL\Logging\DebugStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    private UserInterface $user;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    private function checkUserAuthentication(): void
    {
        $this->user = $this->getUser();
    }

    #[Route('/api/tasks', name: 'get_tasks', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function get(): JsonResponse
    {
        $debugStack = new DebugStack();
        $this->checkUserAuthentication();

        $tasks = $this->entityManager
            ->getRepository(Task::class)
            ->findBy(['user' => $this->user]);

        return new JsonResponse(['tasks' => $tasks]);
    }

    #[Route('/api/tasks', name: 'create_task', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(
        #[MapRequestPayload] CreateRequest $request,
    ): JsonResponse
    {
        $this->checkUserAuthentication();

        $task = new Task(
            $request->title,
            $request->description,
            $this->user,
            $request->completed
        );

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new JsonResponse(['task' => $task]);
    }

    #[Route('/api/tasks/{id}', name: 'update_task', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function update(
        int $id,
        #[MapRequestPayload] UpdateRequest $request,
    ): JsonResponse
    {
        $this->checkUserAuthentication();

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['id' => $id]);

        if (!$task) {
            return new JsonResponse(['message' => 'Task not found'], 404);
        }

        if ($task->user->id !== $this->user->id) {
            return new JsonResponse(['message' => 'Access denied'], 403);
        }

        $task->title = $request->title;
        $task->description = $request->description;
        $task->completed = $request->completed;

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return new JsonResponse(['task' => $task]);
    }

    #[Route('/api/tasks/{id}', name: 'delete_task', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function delete(int $id): JsonResponse
    {
        $this->checkUserAuthentication();

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['id' => $id]);

        if (!$task) {
            return new JsonResponse(['message' => 'Task not found'], 404);
        }

        if ($task->user->id !== $this->user->id) {
            return new JsonResponse(['message' => 'Access denied'], 403);
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Task deleted successfully']);
    }
}
