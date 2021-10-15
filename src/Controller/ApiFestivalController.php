<?php

namespace App\Controller;

use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiFestivalController extends AbstractController
{
    /**
     * @Route("/api/festival", name="api_festival_index", methods={"GET"})
     */
    public function index(FestivalRepository $festivalRepository, NormalizerInterface $normalizer)
    {
//        $festivals = $festivalRepository->findAll();
//
//        $festivalsNormalized = $normalizer->normalize($festivals, null, ['groups' => 'festival:read']);
//
//        $json = json_encode($festivalsNormalized);
//
//        $response = new Response($json, 200, [
//            "Content-Type" => "application/json"
//        ]);
//
//        return $response;
    }
}
