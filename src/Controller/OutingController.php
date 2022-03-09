<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Outing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingController extends AbstractController
{
    /**
     * @Route("/outing", name="app_outing")
     */
    public function index(Request $req, EntityManagerInterface $mgr): Response
    {

        if ('submit' === $req->query->get('submit')) {
            $searchCampus = $mgr->getRepository(Campus::class)->find($req->query->get('campus'));
            
            $outings = $mgr->getRepository(Outing::class)->findBy(array('campus' => $searchCampus));
        } else {
            $outings = $mgr->getRepository(Outing::class)->findAll();
            //TODO : à mettre en place une fois les log en place
            //$outings = $mgr->getRepository(Outing::class)->findBy(array('campus' => $this->getUser()->getCampus()));
        }
        $campus = $mgr->getRepository(Campus::class)->findAll();

        return $this->render('outing/index.html.twig', [
            'controller_name' => 'OutingController',
            'outings' => $outings,
            'campus' => $campus,
        ]);
    }
}
