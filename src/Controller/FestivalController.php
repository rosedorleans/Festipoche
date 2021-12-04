<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/festival")
 */
class FestivalController extends AbstractController
{
    /**
     * @Route("/", name="festival_index", methods={"GET"})
     */
    public function index(FestivalRepository $festivalRepository)
    {
        $allFestivals = $festivalRepository->findAll();
        $arrayCollection = array();

        foreach($allFestivals as $festival) {
            $arrayCollection[] = array(
                'id' => $festival->getId(),
                'name' => $festival->getName(),
                'startDate' => $festival->getStartDate(),
                'endDate' => $festival->getEndDate()
            );
        }

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/new", name="festival_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $festival = new Festival();
        $form = $this->createForm(FestivalType::class, $festival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($festival);
            $entityManager->flush();

            return new JsonResponse("Festival créé", 200);
        }

        return new JsonResponse("Ajouter un festival", 200);
    }

    /**
     * @Route("/{id}", name="festival_show", methods={"GET"})
     */
    public function show(EntityManagerInterface $entityManager, Request $request)
    {
        $festival = $entityManager->getRepository('App:Festival')->findOneBy(['id' => $request->get('id')]);
        $arrayCollection[] = array(
            'id' => $festival->getId(),
            'name' => $festival->getName(),
            'startDate' => $festival->getStartDate(),
            'endDate' => $festival->getEndDate()
        );

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/{id}/edit", name="festival_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Festival $festival): Response
    {
        $form = $this->createForm(FestivalType::class, $festival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse("Festival modifié", 200);
        }

        return new JsonResponse("Modifier un festival", 200);
    }

    /**
     * @Route("/{id}/delete", name="festival_delete", methods={"POST"})
     */
    public function delete(Request $request, Festival $festival): Response
    {
        if ($this->isCsrfTokenValid('delete'.$festival->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($festival);
            $entityManager->flush();

            return new JsonResponse("Festival supprimé", 200);
        }

        return new JsonResponse("Supprimer un festival", 200);
    }
}
