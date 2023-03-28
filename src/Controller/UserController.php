<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
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

}