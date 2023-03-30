<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class HomepageController extends AbstractController
{
    #[Route(path: '/', name: 'app_homepage')]
    public function start(): Response
    {
        $user = $this->getUser();
        if($user == null){
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        else{
            $roles = $user->getRoles();
            if(in_array("ROLE_ADMIN", $roles))
                return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
            else
                return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
                
        }
    }
    
}