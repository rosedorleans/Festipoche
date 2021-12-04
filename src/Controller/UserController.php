<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse("Utilisateur créé", 200);
        }

        return new JsonResponse("Créer un utilisateur", 200);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $entityManager->getRepository('App:User')->findOneBy(['id' => $request->get('id')]);
        $arrayCollection[] = array(
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword()
        );

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse("Utilisateur modifié", 200);
        }

        return new JsonResponse("Modifier un utilisateur", 200);
    }

    /**
     * @Route("/{id}/delete", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            return new JsonResponse("Utilisateur supprimé", 200);
        }

        return new JsonResponse("Supprimer un utilisateur", 200);
    }
}
