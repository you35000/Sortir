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

class OutingController extends AbstractController
{
    /**
     * @Route("/outing", name="app_outing")
     */
    public function index(Request $req, EntityManagerInterface $mgr): Response
    {
        $user = $mgr->getRepository(User::class)->find(1);
        $outings = $mgr->getRepository(Outing::class)->findAll();
        $search = new SearchOuting();
        $form = $this->createForm(SearchFormType::class, $search);
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $outings = $mgr->getRepository(Outing::class)->filters($search, $user);
        }
        //TODO : à mettre en place une fois les log en place
        //$outings = $mgr->getRepository(Outing::class)->findBy(array('campus' => $this->getUser()->getCampus()));

        $campus = $mgr->getRepository(Campus::class)->findAll();

        return $this->render('outing/index.html.twig', [
            'controller_name' => 'OutingController',
            'user' => $user,
            'outings' => $outings,
            'campus' => $campus,
            'form'=>$form->createView(),
        ]);
    }
}
