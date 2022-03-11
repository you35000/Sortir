<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Outing;
use App\Entity\User;
use App\Entity\State;
use App\Form\Model\SearchOuting;
use App\Form\SearchFormType;
use App\Repository\OutingRepository;
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

        $outings = $mgr->getRepository(Outing::class)->findAllNotHistorized($this->getUser());
        $campus = $mgr->getRepository(Campus::class)->findAll();

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $search = $form->getData();
            $outings = $mgr->getRepository(Outing::class)->filters($search, $this->getUser());
        }

        return $this->render('outing/index.html.twig', [
            'controller_name' => 'OutingController',
            'outings' => $outings,
            'campus' => $campus,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/outing_details/{id}", name="outing_details")
     */
    public function details(Outing $outing, EntityManagerInterface $mgr)
    {
        return $this->render('outing/detail.html.twig', [
            'outing' => $outing,
        ]);
    }

    /**
     * @Route("/published/{id}", name="outing_published")
     */
    public function published(Outing $outing, EntityManagerInterface $mgr)
    {
        $outing->setState($mgr->getRepository(State::class)->findOneBy(['libelle' => 'Ouverte']));

        $mgr->persist($outing);
        $mgr->flush();

        return $this->redirectToRoute('app_outing');
    }

    /**
     * @Route ("/withdraw-outing/{id}", name="withdraw")
     */
    public function withdraw(Outing $outing, EntityManagerInterface $em): Response
    {
        if (($outing->getAttendees()->contains(($this->getUser())) || ($outing->getAttendees()->count() == $outing->getNbInscription()))) {
            return $this->redirectToRoute('app_outing');
        } else {
            $outing->removeAttendee($this->getUser());
            $em->persist($outing);
            $em->flush();
            return $this->redirectToRoute('app_outing');
        }
    }

    /**
     * @Route ("/register-outing/{id}", name="register")
     */
    public function register(Outing $outing, EntityManagerInterface $em): Response
    {
        if (($outing->getAttendees()->contains($this->getUser())) || ($outing->getAttendees()->count() == $outing->getNbInscription())) {
            return $this->redirectToRoute('app_outing');
        } else {
            $outing->addAttendee($this->getUser());
            $em->persist($outing);
            $em->flush();
            return $this->redirectToRoute('app_outing');
        }
    }

}
