<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api', name: 'api_')]
class APIController extends AbstractController
{
    #[Route('/utilisateurs/liste', name: 'liste', methods: ['GET'])]
    public function liste(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $userRepository->findAll();
        $jsonUserList = $serializer->serialize($user, 'json');
        return new JsonResponse($jsonUserList, Response::HTTP_OK, [], true);
    }

    #[Route('/utilisateurs/{user}', name: 'detailUser', methods: ['GET'])]
    public function getDetailUser(User $user, SerializerInterface $serializer): JsonResponse
    { {
            $jsonUser = $serializer->serialize($user, 'json');
            return new JsonResponse($jsonUser, Response::HTTP_OK, ['accept' => 'json'], true);
        }
    }

    
    
    #[Route('/utilisateurs/{user}', name: 'deleteUser', methods: ['DELETE'])]
    public function deleteBook(User $user, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
