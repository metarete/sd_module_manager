<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfiloFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/profilo')]
class ProfiloController extends AbstractController
{

    #[Route('/show', name: 'app_profilo_show', methods: ['GET', 'POST'])]
    public function show(): Response
    {
        $user = $this->getUser();

        if ($user == null) {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Profilo/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_profilo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {


        $form = $this->createForm(ProfiloFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_profilo_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Profilo/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/password/{id}', name: 'app_profilo_password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request, User $user, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        $email = $user->getEmail();
        $emailDiInput = $request->get('email');
        $passwordVecchiaInMemoria = $user->getPassword();
        $passwordVecchia = $request->get('password_vecchia');
        $passwordNuova = $request->get('password_nuova');
        $confermaPassword = $request->get('conferma_password');


        if ($passwordNuova == $confermaPassword && $passwordNuova != null) {

            if ($email != $emailDiInput || $emailDiInput == null) {
                return new Response('Email non valida');
            } else if ($email == $emailDiInput && $emailDiInput != null) {
                if ($passwordVecchia == null) {
                    return new Response('Valore password vecchia mancante');
                } else if ($passwordVecchia != null) {
                    if ( !$passwordHasher->isPasswordValid($user, $passwordVecchia)) {
                        
                        return new Response('Password vecchia errata');
                    } else {
                        //tutto corretto
                    }
                }
            }
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $passwordNuova
            );
            
            $user->setPassword($hashedPassword);
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_profilo_show', [], Response::HTTP_SEE_OTHER);
        } else if ($passwordNuova != $confermaPassword && $passwordNuova != null) {
            return new Response('Le password non coincidono');
        }


        return $this->renderForm('Profilo/editPassword.html.twig', [
            'user' => $user,

        ]);
    }
}
