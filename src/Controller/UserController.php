<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/internal")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/display-user/{outing}/{user}", name="display_user")
     */
    public function displayUser(Outing $outing, User $user): Response
    {
        return $this->render('user/displayProfil.html.twig', [
            'user' => $user,
            'outing' => $outing
        ]);
    }

    /**
     * @Route("/display-organizer/{id}", name="display_organizer")
     */
    public function displayOrganizer(User $user): Response
    {
        return $this->render('user/displayProfil.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/display-myprofile/", name="display_my_profile")
     */
    public function displayMyprofil(EntityManagerInterface $manager, Request $req, SluggerInterface $slugger, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('password')->getData();
            if ($password) {
                $hashedPassword = $hasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }
            // TODO : à mettre dans un service
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

            $manager->persist($user);
            $manager->flush();

            $this->redirectToRoute('app_outing');
        }

        return $this->render('user/myProfile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
