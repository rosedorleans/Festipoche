<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Form\StageType;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stage")
 */
class StageController extends AbstractController
{
    /**
     * @Route("/", name="stage_index", methods={"GET"})
     */
    public function index(StageRepository $stageRepository): Response
    {
        $allStages = $stageRepository->findAll();
        $arrayCollection = array();

        foreach($allStages as $stage) {
            $arrayCollection[] = array(
                'id' => $stage->getId(),
                'name' => $stage->getName(),
                'festival' => $stage->getFestival(),
                'events' => $stage->getEvents()
            );
        }

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/new", name="stage_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $stage = new Stage();
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stage);
            $entityManager->flush();

            return new JsonResponse("Scène créée", 200);        }

        return new JsonResponse("Ajouter une scène", 200);
    }

    /**
     * @Route("/{id}", name="stage_show", methods={"GET"})
     */
    public function show(EntityManagerInterface $entityManager, Request $request): Response
    {
        $stage = $entityManager->getRepository('App:Stage')->findOneBy(['id' => $request->get('id')]);
        $arrayCollection[] = array(
            'id' => $stage->getId(),
            'name' => $stage->getName(),
            'festival' => $stage->getFestival(),
            'events' => $stage->getEvents()
        );

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/{id}/edit", name="stage_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Stage $stage): Response
    {
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse("Scène modifiée", 200);        }

        return new JsonResponse("Modifier une scène", 200);

    }

    /**
     * @Route("/{id}", name="stage_delete", methods={"POST"})
     */
    public function delete(Request $request, Stage $stage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stage);
            $entityManager->flush();

            return new JsonResponse("Scène supprimée", 200);
        }

        return new JsonResponse("Supprimer une scène", 200);        }
}
