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

        if ($req->request->get('submit')) {
            $searchCampus = $mgr->getRepository(Campus::class)->find($req->request->get('campus'));
            $outings = $mgr->getRepository(Outing::class)->findBy(array('campus' => $searchCampus));
            if ($req->request->get('dateStarted')) {
                $dateStarted = date_create_from_format('Y-m-d', $req->request->get('dateStarted'));
                $outings = array_filter($outings, (function ($o) use ($dateStarted) {
                    if ($o->getStartDate() > $dateStarted) {
                        return $o;
                    }
                }));
            }
            if ($req->request->get('dateEnded')) {
                $dateEnded = date_create_from_format('Y-m-d', $req->request->get('dateEnded'));
                $outings = array_filter($outings, (function ($o) use ($dateEnded) {
                    if ($o->getStartDate() < $dateEnded) {
                        return $o;
                    }
                }));
            }
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
