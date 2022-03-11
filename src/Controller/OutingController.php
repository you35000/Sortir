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
     * @Route ("/withdraw-outing/{id}", name="outing_withdraw")
     */
    public function withdrawOuting(int $id, OutingRepository $outingRepository, EntityManagerInterface $entityManager): Response
    {
        $outing = $outingRepository->find($id);
        dd($outing);
        $currentUser = $this->getUser();
        $currentUser->withdrawOuting($outing);
        $entityManager->flush();

        return $this->render('outing/index.html.twig', ['outing' => $outing,]);
    }

    /**
     * @Route ("/register-outing/{id}", name="outing_register")
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

    /**
     * @Route ("/cancel-outing/{id}", name="outing_cancel")
     */
    public function cancel(Outing $outing, EntityManagerInterface $em): Response
    {
        if ($outing->getOrganizer() == $this->getUser() && $outing->getStartDate() > new \DateTime('now')) {
            $outing->setState($em->getRepository(State::class)->findOneBy(['libelle'=>'Annulée']));
            $em->persist($outing);
            $em->flush();
            return $this->redirectToRoute('app_outing');
        } else {
            $message = 'Impossible d\'annuler la sortie';
            //TODO : envoyer le message
            return $this->redirectToRoute('app_outing');
        }
    }

    /**
     * @Route ("/delete-outing/{id}", name="outing_delete")
     */
    public function remove(Outing $outing, EntityManagerInterface $em): Response
    {
        if (($outing->getOrganizer() == $this->getUser()) && ($outing->getState()->getLibelle() == 'Créée')) {
            $em->remove($outing);
            $em->flush();
            return $this->redirectToRoute('app_outing');
        } else {
            $message = 'Impossible de supprimer la sortie';
            //TODO : envoyer le message
            return $this->redirectToRoute('app_outing');
        }
    }

    /**
     * @Route ("/update-outing/{id}", name="outing_update")
     */
    public function update(Outing $outing, EntityManagerInterface $em): Response
    {
        return $this->render('outing/update.html.twig',[
            'outing'=>$outing,
        ]);
    }

}
