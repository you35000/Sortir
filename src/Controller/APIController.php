<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/places/{id}", name="place_get", methods={"GET"})
     */
    public function getOnePlace(Place $place): Response
    {
        return $this->json($place);
    }

    /**
     * @Route("/places/", name="places_post", methods="POST")
     */
    public function post(CityRepository $repo, Request $req, EntityManagerInterface $em): Response
    {
        $faker = Factory::create('fr_FR');
        $place = new Place();

        $data = json_decode($req->getContent());
        if ($data) {
            $city = $repo->find($data->city->id);

            $place->setCity($city);
            $place->setLatitude($faker->latitude);
            $place->setLongitude($faker->longitude);
            $place->setName($data->name);
            $place->setStreet($data->street);
        }
        if ($place->getName() && $place->getStreet() && $place->getCity()) {

            $em->persist($place);
            $em->flush();
            return $this->json($place);
        }
        return $this->redirectToRoute('new_outing');
    }

}
