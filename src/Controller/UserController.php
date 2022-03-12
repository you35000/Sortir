<?php

namespace App\Controller;


use App\Entity\Outing;
use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/internal")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/afficherProfil/{outing}/{user}", name="afficherProfil")
     */
    public function afficherProfil(Outing $outing, User $user, Request $request): Response
    {
        return $this->render('user/afficherProfil.html.twig', [
            'user' => $user,
            'outing' => $outing
        ]);
    }

    /**
     * @Route("/monProfil/", name="monProfil")
     */

    public function ajouterProfil(Request $req, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('uploads_user_pictures'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd('oups : prob\'s happened');
                }

                $user->setPicture($newFilename);
            }

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_outing');
        }
        return $this->render('user/monProfil.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);

    }

}