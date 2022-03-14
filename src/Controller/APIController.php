<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/cities", name="cities_get", methods={"GET"})
     */
    public function getCities(CityRepository $cityRepository): JsonResponse
    {
        return $this->json($cityRepository->findAll());
    }

    /**
     * @Route("/places", name="places_get", methods={"GET"})
     */
    public function getPlaces(PlaceRepository $placeRepository): Response
    {
        return $this->json($placeRepository->findAll());
    }

    /**
     * @Route("/testApi", name="test_api")
     */
    public function index(): Response
    {
        return $this->render('api/index.html.twig');
    }
}
