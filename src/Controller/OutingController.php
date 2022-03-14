<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Outing;
use App\Entity\User;
use App\Entity\State;
use App\Form\Model\SearchOuting;
use App\Form\OutingFormType;
use App\Form\SearchFormType;
use App\Repository\OutingRepository;
use App\Repository\PlaceRepository;
use App\Repository\UserRepository;
use App\Service\UpdateState;
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
    public function index(Request $req, EntityManagerInterface $mgr, UpdateState $updateState): Response
    {
        $updateState->updateOutings();
        $outings = $mgr->getRepository(Outing::class)->findAllNotHistorized($this->getUser());
        $campus = $mgr->getRepository(Campus::class)->findAll();

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
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
        if ($outing->getState()->getLibelle() == 'Créée' && $outing->getOrganizer() === $this->getUser()) {
            $outing->setState($mgr->getRepository(State::class)->findOneBy(['libelle' => 'Ouverte']));
            $mgr->persist($outing);
            $mgr->flush();
        }

        return $this->redirectToRoute('app_outing');
    }

    /**
     * @Route ("/withdraw-outing/{id}", name="outing_withdraw")
     */
    public function withdraw(Outing $outing, EntityManagerInterface $em): Response
    {
        if (($outing->getAttendees()->contains(($this->getUser())))) {
            $outing->removeAttendee($this->getUser());
            if (($outing->getAttendees()->count() < $outing->getNbInscription())) {
                $outing->setState($em->getRepository(State::class)->findOneBy(['libelle' => 'Ouverte']));
            }
            $em->persist($outing);
            $em->flush();
            return $this->redirectToRoute('app_outing');
        } else {

            return $this->redirectToRoute('app_outing');
        }
    }

    /**
     * @Route ("/register-outing/{id}", name="outing_register")
     */
    public function register(Outing $outing, EntityManagerInterface $em): Response
    {   //l'utilisateur est-il contenu dans les participants de la sortie (booleen ?)
        if (($outing->getAttendees()->contains($this->getUser())) || ($outing->getAttendees()->count() == $outing->getNbInscription())) {
            return $this->redirectToRoute('app_outing');
        } else {
            $outing->addAttendee($this->getUser());
            //on prend le nombre de participants , on affiche cloturé quand le dernier participant est inscrit
            if ($outing->getAttendees()->count() == $outing->getNbInscription()) {
                $outing->setState($em->getRepository(State::class)->findOneBy(['libelle' => 'Clôturée']));
            };
            $em->persist($outing);
            $em->flush();
            return $this->redirectToRoute('app_outing');
        }
    }

    /**
     * @Route ("/cancel-outing/{id}", name="outing_cancel")
     */
    public function cancel(Outing $outing, Request $req, EntityManagerInterface $em): Response
    {
        $message = '';
        if ($req->request->get('submit')) {
            if ($outing->getOrganizer() === $this->getUser() && $outing->getStartDate() > new \DateTime('now')) {
                $outing->setState($em->getRepository(State::class)->findOneBy(['libelle' => 'Annulée']));
                $outing->setOutingInfo('!!! ANNULATION !!! ' . $req->request->get('outingInfo'));
                $em->persist($outing);
                $em->flush();
                return $this->redirectToRoute('app_outing');
            } else {
                $message = 'Impossible d\'annuler la sortie';
            }
        }

        return $this->render('outing/cancel.html.twig', [
            'outing' => $outing,
            'message' => $message
        ]);
    }

    /**
     * @Route ("/delete-outing/{id}", name="outing_delete")
     */
    public function remove(Outing $outing, EntityManagerInterface $em): Response
    {
        if (($outing->getOrganizer() === $this->getUser()) && ($outing->getState()->getLibelle() == 'Créée')) {
            $em->remove($outing);
            $em->flush();
        } else {
            $message = 'Impossible de supprimer la sortie';
            //TODO : envoyer le message
        }
        return $this->redirectToRoute('app_outing');
    }

    /**
     * @Route ("/update-outing/{id}", name="outing_update")
     */
    public function update(Outing $outing, EntityManagerInterface $em): Response
    {
        return $this->render('outing/update.html.twig', [
            'outing' => $outing,
        ]);
    }

    /**
     * @Route ("/new-outing/", name="outing_new")
     */
    public function new(Request $req, EntityManagerInterface $em): Response
    {
        $outing = new Outing();
        dd($em->getRepository(Campus::class)->find(34)->getName());
        $form = $this->createForm(OutingFormType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $newOuting = $form->getData();
            $newOuting->setOrganizer($this->getUser());
            $newOuting->setCampus($this->getUser()->getCampus());
            if ($form->getClickedButton()->getConfig()->getName() == 'create') {
                $newOuting->setState($em->getRepository(State::class)->findOneBy(['libelle' => 'Créée']));
            } else {
                $newOuting->setState($em->getRepository(State::class)->findOneBy(['libelle' => 'Ouverte']));
            };

            $em->persist($newOuting);
            $em->flush();
            return $this->redirectToRoute('app_outing');
        }

        return $this->render('outing/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/consult-outing/{id}", name="consult_outing",  requirements={"id"="\d+"})
     */

    public function consult(Request $req, OutingRepository $o, PlaceRepository $p, UserRepository $u): Response
    {
        $today = new \DateTime('now');
        $OneMonthAgo = $today->sub(new \DateInterval('P1M'));
        $idOuting = $req->get('id');
        $outing = $o->find($idOuting);
        if ($outing->getStartDate() < $OneMonthAgo) {
            $this->addFlash('danger', 'la sortie a expirée');
            return $this->redirectToRoute('default_home');
        }
        $lieu = $p->find($idOuting);
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('outing/consultOuting.html.twig', [
            'outing' => $outing,
            'place' => $p,
            'user' => $user
        ]);
    }
}
