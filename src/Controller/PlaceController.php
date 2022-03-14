<?php

namespace App\Controller;

use App\Entity\Place;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    /**
     * @Route("/places/", name="places")
     */
    public function places()
    {

        return $this->render('place/places.html.twig', [
            'places' => $this->getDoctrine()->getManager()->getRepository(Place::class)->findAll(),
        ]);
    }

    /**
     * @Route("/update-place/{id}", name="update_place")
     */
    public function updatePlace(Place $place): Response
    {

        return $this->render('place/places.html.twig', [
            '$place' => $this->getDoctrine()->getManager()->getRepository(Place::class)->findAll(),
        ]);
    }

    /**
     * @Route("/delete-place/{id}", name="delete_place")
     */
    public function deletePlace(Place $place, EntityManagerInterface $em): Response
    {
        $em->remove($place);
        $em->flush();
        return $this->render('place/places.html.twig', [
            '$place' => $this->getDoctrine()->getManager()->getRepository(Place::class)->findAll(),
        ]);
    }
}
