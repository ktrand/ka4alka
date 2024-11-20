<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/api/tasks', name: 'get_tasks', method: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function get(): JsonResponse
    {
        $user  = $this->getUser();

        $tasks = $this->entityManager
            ->getRepository(Task::class)
            ->findBy(['user' => $user]);

        return new JsonResponse(['tasks' => $tasks]);
    }
    // #[Route('/api/tasks', name: 'create_task', method: ['POST'])]
    // #[IsGranted('ROLE_USER')]
    // public function create(
    //     #[MapRequestPayload] Create $request,
    // ): JsonResponse {}
}
