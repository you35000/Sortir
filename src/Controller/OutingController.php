<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Outing;
use App\Entity\User;
use App\Form\Model\SearchOuting;
use App\Form\SearchFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/internal")
*/
class OutingController extends AbstractController
{
    /**
     * @Route("/outing", name="app_outing")
     */
    public function index(Request $req, EntityManagerInterface $mgr): Response
    {
        $outings = $mgr->getRepository(Outing::class)->findAllNotHistorized();

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $search = $form->getData();
            $outings = $mgr->getRepository(Outing::class)->filters($search, $this->getUser());
        }

        $outings = $mgr->getRepository(Outing::class)->findBy(array('campus' => $this->getUser()->getCampus()));
        $campus = $mgr->getRepository(Campus::class)->findAll();

        return $this->render('outing/index.html.twig', [
            'controller_name' => 'OutingController',
            'outings' => $outings,
            'campus' => $campus,
            'form' => $form->createView(),
        ]);
    }
}
