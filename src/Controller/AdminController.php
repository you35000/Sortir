<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/cities", name="cities")
     */
    public function cities(CityRepository $cityRepository): Response
    {
        return $this->render('admin/cities.html.twig', [
            'cities' => $cityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/campus", name="campus")
     */
    public function campus(CampusRepository $campusRepository): Response
    {
        return $this->render('admin/campus.html.twig', [
            'campus' => $campusRepository->findAll(),
        ]);
    }

    /**
     * @Route("/update-campus/{id}", name="update_campus")
     */
    public function updateCampus(Campus $campus): Response
    {

        return $this->render('admin/campus.html.twig', [
            'campus' => $this->getDoctrine()->getManager()->getRepository(Place::class)->findAll(),
        ]);
    }

    /**
     * @Route("/delete-campus/{id}", name="delete_campus")
     */
    public function deleteCampus(Campus $campus, EntityManagerInterface $em): Response
    {
        $em->remove($campus);
        $em->flush();
        return $this->render('admin/campus.html.twig', [
            'campus' => $this->getDoctrine()->getManager()->getRepository(Place::class)->findAll(),
        ]);
    }
}
