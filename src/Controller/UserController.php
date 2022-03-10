<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/afficherProfil/{id}", name="afficherProfil")
     */
    public function afficherProfil(User $user, Request $request): Response
    {
        return $this->render('user/afficherProfil.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/monProfil/", name="monProfil")
     */

    public function ajouterProfil(Request $req, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(UserFormType::class, $this->getUser());
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_outing');
        }
        return $this->render('user/monProfil.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }

}

