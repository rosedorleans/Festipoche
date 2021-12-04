<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        $allEvents = $eventRepository->findAll();
        $arrayCollection = array();

        foreach($allEvents as $event) {
            $arrayCollection[] = array(
                'start_datetime' => $event->getStartDatetime(),
                'end_datetime' => $event->getEndDatetime(),
                'stage' => $event->getStage(),
                'artists' => $event->getArtists()
            );
        }
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return new JsonResponse("Evenement créé", 200);        }

        return new JsonResponse("Ajouter un evenement", 200);
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     */
    public function show(EntityManagerInterface $entityManager, Request $request): Response
    {
        $event = $entityManager->getRepository('App:Artist')->findOneBy(['id' => $request->get('id')]);
        $arrayCollection[] = array(
            'start_datetime' => $event->getStartDatetime(),
            'end_datetime' => $event->getEndDatetime(),
            'stage' => $event->getStage(),
            'artists' => $event->getArtists()
        );
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse("Artiste modifié", 200);        }

        return new JsonResponse("Modifier un artiste", 200);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"POST"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();

            return new JsonResponse("Evenement supprimé", 200);
        }

        return new JsonResponse("Supprimer un evenement", 200);       }
}
