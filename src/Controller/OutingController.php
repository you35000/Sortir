<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Entity\Campus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingController extends AbstractController
{
    /**
     * @Route("/outing", name="app_outing")
     */
    public function index(): Response
    {
        $outings = $this->getDoctrine()->getManager()->getRepository(Outing::class)->findAll();
        $campus = $this->getDoctrine()->getManager()->getRepository(Campus::class)->findAll();
        return $this->render('outing/index.html.twig', [
            'controller_name' => 'OutingController',
            'outings' => $outings,
            'campus' => $campus,
        ]);
    }
}
