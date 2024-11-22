<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\DTO\LoginRequest;
use App\DTO\RegisterRequest;

class AuthController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload] RegisterRequest $registerRequest,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): JsonResponse 
    {
        $user = new User();
        $user->setEmail($registerRequest->email);
        $user->setRoles(['ROLE_USER']);
        
        $hashedPassword = $passwordHasher->hashPassword($user, $registerRequest->password);
        $user->setPassword($hashedPassword);
        
        $user->setApiToken(bin2hex(random_bytes(32)));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'Регистрация успешна',
            'api_token' => $user->getApiToken()
        ]);
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        #[MapRequestPayload] LoginRequest $loginRequest,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): JsonResponse 
    {
        $email = $loginRequest->email;
        $password = $loginRequest->password;
        
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        
        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json([
                'message' => 'Неверные учетные данные'
            ], 401);
        }

        return $this->json([
            'api_token' => $user->getApiToken()
        ]);
    }
} 