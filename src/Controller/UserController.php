<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Form\UserFormEditType;
use App\Form\ChangePasswordFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $hashedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            $user->setPassword($hashedPassword);
            $roles[0] = "ROLE_ADMIN";
            $user->setRoles($roles);
            $user->setStato(true);
            $user->setUsername("");
            $userRepository->add($user, true);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    
    #[Route('/{page}', name: 'app_user_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, UserRepository $userRepository, ParameterBagInterface $params, int $page = 1): Response
    {
        //sessione
        $session = $request->getSession();

        $numeroUsersVisibiliPerPagina = $request->request->get('filtro_numero_users');

        if($request->request->get('filtro_ricerca') != null && $request->request->get('filtro_ricerca') != ""){
            $session->set('filtro_ricerca', $request->request->get('filtro_ricerca'));
        }
        else{
            $session->set('filtro_ricerca', null);
        }

        if ($numeroUsersVisibiliPerPagina == null)
            $usersPerPagina = 10;
        else
            $usersPerPagina = $numeroUsersVisibiliPerPagina;

        $offset = $usersPerPagina * $page - $usersPerPagina;


        $users = $userRepository->findBy([], array('id' => 'DESC'), $usersPerPagina, $offset);

        //utilizzo sistema di ricerca
        if($session->get('filtro_ricerca') != null && $session->get('filtro_ricerca') != ""){
            $userTrovati1 = $userRepository->findByBarraRicerca($session->get('filtro_ricerca'));
            $users = [];
            //costruisco l'elenco schede in base al filtro e alla pagina
             for($i=(($page - 1) * $usersPerPagina); $i<$usersPerPagina*$page; $i++){
                if($i<count($userTrovati1))
                    array_push($users,$userTrovati1[$i]);     
            }
        }

        //calcolo pagine per paginatore
        //senza ricerca nella barra
        if($session->get('filtro_ricerca') == null && $session->get('filtro_ricerca') == ""){
            $totaleUsers = $userRepository->contaOperatori();
            $pagineTotali = ceil($totaleUsers / $usersPerPagina);
        }
        //con la ricerca della barra
        else{
            $totaleUsers = $userRepository->contaOperatoriInRicerca($session->get('filtro_ricerca'));
            $pagineTotali = ceil($totaleUsers / $usersPerPagina);
        }

        
         
        //url per impersonificazione
        $url = $params->get('app.site_url');
        $url = $url .'/scadenzario?_switch_user=';

        if ($pagineTotali == 0)
            $pagineTotali = 1;

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'pagina' => $page,
            'ricerca' => $session->get('filtro_ricerca'),
            'pagine_totali' => $pagineTotali,
            'users_per_pagina' => $usersPerPagina,
            'url' => $url,
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
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserFormEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);    
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/edit_password/{id}', name: 'app_user_password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request, User $user, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $passwordNuova = $form->get('plainPassword')->getData();
            
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $passwordNuova
            );
            
            $user->setPassword($hashedPassword);
            $userRepository->add($user, true);
            
            return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Profilo/editPassword.html.twig', [
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

    #[Route('/attiva/{id}', name: 'app_user_attiva', methods: ['GET', 'POST'])]
    public function attiva(User $user, UserRepository $userRepository): Response
    {
        $user->setStato(true);
        $userRepository->add($user, true);

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/disattiva/{id}', name: 'app_user_disattiva', methods: ['GET', 'POST'])]
    public function disattiva(User $user, UserRepository $userRepository): Response
    {
        $user->setStato(false);
        $userRepository->add($user, true);

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

   
    

}