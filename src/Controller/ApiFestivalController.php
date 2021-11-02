<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\FestivalRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiFestivalController extends AbstractController
{
    // FESTIVALS INFOS GET
    /**
     * @Route("/api/festival", name="api_festival_index", methods={"GET"})
     */
    public function index(FestivalRepository $festivalRepository, UserRepository $userRepository): JsonResponse
    {
        return $this->json($festivalRepository->findAll(), 200, [], ['groups' => 'festival:read']);
    }

    // USER INFOS GET
    /**
     * @Route("/api/festival/user", name="api_festival_user", methods={"GET"})
     */
    public function user(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll(), 200, [], ['groups' => 'user:read']);
    }

    // USER INFOS POST
    /**
     * @Route("/api/festival/user", name="api_festival_user", methods={"POST"})
     */
    public function postUser(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    )
    {
        $jsonReceived = $request->getContent();

        try {
            $user = $serializer->deserialize($jsonReceived, User::class, 'json');

            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json($user, 201, [], ['groups' => 'user:read']);
        } catch (NotEncodableValueException $neve) {
            return $this->json([
                'status'=> 400,
                'message' => $neve->getMessage()
            ], 400);
        }

    }
}
