<?php

namespace App\Controller;

use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\User;
use App\Entity\Paziente;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class ElencoSchedeController extends AbstractController
{
    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/bacheca', name: 'app_bacheca')]
    public function index(): Response
    {
        $user= $this-> getUser();

        $operatoriRepository = $this->entityManager->getRepository(User::class);
        $pazientiRepository = $this->entityManager->getRepository(Paziente::class);
        $schedePaiRepository = $this->entityManager->getRepository(SchedaPAI::class);

        $schedeNuove = count($schedePaiRepository->findByState("nuova"));
        $schedeApprovate = count($schedePaiRepository->findByState("approvata"));
        $schedeAttive = count($schedePaiRepository->findByState("attiva"));
        $schedeInAttesa = count($schedePaiRepository->findByState("in_attesa_di_chiusura"));
        $schedeChiuse = count($schedePaiRepository->findByState("chiusa"));
        $schedeChiuseConRinnovo = count($schedePaiRepository->findByState("chiusa_con_rinnovo"));
        $totaleSchede = count($schedePaiRepository->findAll());
        $totaleOperatori = count($operatoriRepository->findAll());
        $totalePazienti = count($pazientiRepository->findAll());
        $percentualeNuove = (int)(($schedeNuove/$totaleSchede) * 100);
        $percentualeApprovate = (int)(($schedeApprovate/$totaleSchede) * 100);
        $percentualeAttive = (int)(($schedeAttive/$totaleSchede) * 100);
        $percentualeInAttesa = (int)(($schedeInAttesa/$totaleSchede) * 100);
        $percentualeChiuse = (int)(($schedeChiuse/$totaleSchede) * 100);
        $percentualeChiuseConRinnovo = (int)(($schedeChiuseConRinnovo/$totaleSchede) * 100);
        
        return $this->render('bacheca/index.html.twig', [
            'controller_name' => 'ElencoSchedeController',
            'user' => $user,
            'schedeNuove' => $schedeNuove,
            'schedeApprovate' => $schedeApprovate,
            'schedeAttive' => $schedeAttive,
            'schedeInAttesa' => $schedeInAttesa,
            'schedeChiuse' => $schedeChiuse,
            'schedeChiuseConRinnovo' => $schedeChiuseConRinnovo,
            'totaleSchede' => $totaleSchede,
            'totaleOperatori' => $totaleOperatori,
            'totalePazienti' => $totalePazienti,
            'percentualeNuove' => $percentualeNuove,
            'percentualeApprovate' => $percentualeApprovate,
            'percentualeAttive' => $percentualeAttive,
            'percentualeInAttesa' => $percentualeInAttesa,
            'percentualeChiuse' => $percentualeChiuse,
            'percentualeChiuseConRinnovo' => $percentualeChiuseConRinnovo,
                   
        ]);
    }
}
