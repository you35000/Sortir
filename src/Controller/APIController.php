<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;

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
    /**
     * @Route("/places/", name="api_places_post", methods={"POST"})
     */
    public function update(Request $req,EntityManagerInterface $em): Response
    {
        $place = new Place();
        $data = json_decode($req->getContent());
        $place->setCity($data->city);
        $place->setLatitude($data->latitude);
        $place->setLongitude($data->longitude);
        $place->setName($data->name);
        $place->setStreet($data->street);
        $em->persist($place);
        $em->flush();
        return $this->json($place);
    }

}
