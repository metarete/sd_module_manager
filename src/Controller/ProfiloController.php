<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfiloFormType;
use App\Form\ChangePasswordProfiloFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/profilo')]
class ProfiloController extends AbstractController
{
    #[Route('/show', name: 'app_profilo_show', methods: ['GET', 'POST'])]
    public function show(): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Profilo/show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_profilo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ProfiloFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Profilo/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/password/{id}', name: 'app_profilo_password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordProfiloFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $passwordVecchia = $form->get('plainPassword2')->getData();
            $passwordNuova = $form->get('plainPassword')->getData();
            
            if ( !$passwordHasher->isPasswordValid($user, $passwordVecchia)){
                
                $this->addFlash(
                    'Fallimento',
                    'Cambio password fallito! La password vecchia è errata!'
                );
                return $this->redirectToRoute('app_profilo_password', ["id" => $user->getId()], Response::HTTP_SEE_OTHER);
            }
            else{
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $passwordNuova
                );
                
                $user->setPassword($hashedPassword);
                $userRepository->add($user, true);
            }
            
            return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Profilo/editPassword.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
