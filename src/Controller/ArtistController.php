<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/artist")
 */
class ArtistController extends AbstractController
{
    /**
     * @Route("/", name="artist_index", methods={"GET"})
     */
    public function index(ArtistRepository $artistRepository): Response
    {
        $allArtists = $artistRepository->findAll();
        $arrayCollection = array();

        foreach($allArtists as $artist) {
            $arrayCollection[] = array(
                'id' => $artist->getId(),
                'name' => $artist->getName(),
                'event' => $artist->getEvent()
            );
        }
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/new", name="artist_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($artist);
            $entityManager->flush();

            return new JsonResponse("Artiste créé", 200);
        }

        return new JsonResponse("Ajouter un artiste", 200);
    }

    /**
     * @Route("/{id}", name="artist_show", methods={"GET"})
     */
    public function show(EntityManagerInterface $entityManager, Request $request): Response
    {
        $artist = $entityManager->getRepository('App:Artist')->findOneBy(['id' => $request->get('id')]);
        $arrayCollection[] = array(
            'id' => $artist->getId(),
            'name' => $artist->getName(),
            'event' => $artist->getEvent()
        );
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/{id}/edit", name="artist_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Artist $artist): Response
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse("Artiste modifié", 200);
        }
        return new JsonResponse("Modifier un artiste", 200);
    }

    /**
     * @Route("/{id}/delete", name="artist_delete", methods={"POST"})
     */
    public function delete(Request $request, Artist $artist): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artist->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($artist);
            $entityManager->flush();

            return new JsonResponse("Artiste supprimé", 200);
        }

        return new JsonResponse("Supprimer un artiste", 200);
    }
}
