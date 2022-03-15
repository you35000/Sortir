<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/internal")
 */
class PlaceController extends AbstractController
{
    /**
     * @Route("/places/", name="places")
     */
    public function places(PlaceRepository $repo)
    {
        return $this->render('place/places.html.twig', [
            'places' => $repo->findAll(),
        ]);
    }


}
