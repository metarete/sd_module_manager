<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        $passwordNuova = $request->get('password_nuova');
        $confermaPassword = $request->get('conferma_password');

        

        if ($form->isSubmitted() && $form->isValid()) {
            if($passwordNuova == null && $confermaPassword == null){
                $roles[0] = "ROLE_USER";
                $user->setRoles($roles);
                $userRepository->add($user, true);
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
            else if($passwordNuova == $confermaPassword){
                $roles[0] = "ROLE_USER";
                $user->setRoles($roles);
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $passwordNuova
                );
                $user->setPassword($hashedPassword);
                $userRepository->add($user, true);
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
            else{
                return new Response('Le password non coincidono');
            }
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    
    #[Route('/{page}', name: 'app_user_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, UserRepository $userRepository, int $page = 1): Response
    {
        $users = null;
        $numeroUsersVisibiliPerPagina = $request->request->get('filtro_numero_users');
        if ($numeroUsersVisibiliPerPagina == null)
            $usersPerPagina = 10;
        else
            $usersPerPagina = $numeroUsersVisibiliPerPagina;

        $offset = $usersPerPagina * $page - $usersPerPagina;


        $users = $userRepository->findBy([], array('id' => 'DESC'), $usersPerPagina, $offset);

        //calcolo pagine per paginatore
        $totaleUsers = $userRepository->contaOperatori();
        $pagineTotali = ceil($totaleUsers / $usersPerPagina);
         
 
        if ($pagineTotali == 0)
            $pagineTotali = 1;
        return $this->render('user/index.html.twig', [
            'users' => $users,
            'pagina' => $page,
            'pagine_totali' => $pagineTotali,
            'users_per_pagina' => $usersPerPagina,
        ]);
    }

    #[Route('/show/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        $passwordNuova = $request->get('password_nuova');
        $confermaPassword = $request->get('conferma_password');

        if ($form->isSubmitted() && $form->isValid()) {
            if($passwordNuova == null && $confermaPassword == null){
                $userRepository->add($user, true);
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
            else if($passwordNuova == $confermaPassword){
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $passwordNuova
                );
                $user->setPassword($hashedPassword);
                $userRepository->add($user, true);
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
            else{
                return new Response('Le password non coincidono');
            }
            

            
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/promuovi_admin/{id}', name: 'app_user_promuovi_admin', methods: ['GET', 'POST'])]
    public function promuoviAdmin(User $user, UserRepository $userRepository): Response
    {

        $roles[0] = "ROLE_ADMIN";
        $user->setRoles($roles);
        $userRepository->add($user, true);

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/rendi_user/{id}', name: 'app_user_rendi_user', methods: ['GET', 'POST'])]
    public function rendiUser(User $user, UserRepository $userRepository): Response
    {

        $roles[0] = "ROLE_USER";
        $user->setRoles($roles);
        $userRepository->add($user, true);

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

   
    

}