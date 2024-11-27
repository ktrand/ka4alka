<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/api/users', name: 'api_users', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(): JsonResponse
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        
        $usersArray = array_map(function(User $user) {
            return [
                'id' => $user->id,
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ];
        }, $users);
        
        return new JsonResponse(['users' => $usersArray]);
    }   
} 
