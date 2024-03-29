<?php

namespace App\Controller;

use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\User;
use App\Entity\Paziente;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class BachecaController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/bacheca', name: 'app_bacheca')]
    public function index(): Response
    {
        $operatoriRepository = $this->entityManager->getRepository(User::class);
        $pazientiRepository = $this->entityManager->getRepository(Paziente::class);
        $schedePaiRepository = $this->entityManager->getRepository(SchedaPAI::class);

        $schedeNuove = count($schedePaiRepository->findBy(['currentPlace' => 'nuova']));
        $schedeApprovate = count($schedePaiRepository->findBy(['currentPlace' => 'approvata']));
        $schedeAttive = count($schedePaiRepository->findBy(['currentPlace' => 'attiva']));
        $schedeInAttesa = count($schedePaiRepository->findBy(['currentPlace' => 'in_attesa_di_chiusura']));
        $schedeInAttesaConRinnovo = count($schedePaiRepository->findBy(['currentPlace' => 'in_attesa_di_chiusura_con_rinnovo']));
        $schedeVerifica = count($schedePaiRepository->findBy(['currentPlace' => 'verifica']));
        $schedeChiuse = count($schedePaiRepository->findBy(['currentPlace' => 'chiusa']));
        $schedeChiuseConRinnovo = count($schedePaiRepository->findBy(['currentPlace' => 'chiusa_con_rinnovo']));
        $totaleSchede = count($schedePaiRepository->findAll());
        $totaleOperatori = count($operatoriRepository->findAll());
        $totalePazienti = count($pazientiRepository->findAll());

        if ($totaleSchede == 0) {
            $percentualeNuove = 0;
            $percentualeApprovate = 0;
            $percentualeAttive = 0;
            $percentualeInAttesa = 0;
            $percentualeInAttesaConRinnovo = 0;
            $percentualeVerifica = 0;
            $percentualeChiuse = 0;
            $percentualeChiuseConRinnovo = 0;
        } else {
            $percentualeNuove = (int)(($schedeNuove / $totaleSchede) * 100);
            $percentualeApprovate = (int)(($schedeApprovate / $totaleSchede) * 100);
            $percentualeAttive = (int)(($schedeAttive / $totaleSchede) * 100);
            $percentualeInAttesa = (int)(($schedeInAttesa / $totaleSchede) * 100);
            $percentualeInAttesaConRinnovo = (int)(($schedeInAttesaConRinnovo / $totaleSchede) * 100);
            $percentualeVerifica = (int)(($schedeVerifica / $totaleSchede) * 100);
            $percentualeChiuse = (int)(($schedeChiuse / $totaleSchede) * 100);
            $percentualeChiuseConRinnovo = (int)(($schedeChiuseConRinnovo / $totaleSchede) * 100);
        }
        
        return $this->render('bacheca/index.html.twig', [
            'controller_name' => 'BachecaController',
            'user' => $this->getUser(),
            'schedeNuove' => $schedeNuove,
            'schedeApprovate' => $schedeApprovate,
            'schedeAttive' => $schedeAttive,
            'schedeInAttesa' => $schedeInAttesa,
            'schedeInAttesaConRinnovo' => $schedeInAttesaConRinnovo,
            'schedeVerifica' => $schedeVerifica,
            'schedeChiuse' => $schedeChiuse,
            'schedeChiuseConRinnovo' => $schedeChiuseConRinnovo,
            'totaleSchede' => $totaleSchede,
            'totaleOperatori' => $totaleOperatori,
            'totalePazienti' => $totalePazienti,
            'percentualeNuove' => $percentualeNuove,
            'percentualeApprovate' => $percentualeApprovate,
            'percentualeAttive' => $percentualeAttive,
            'percentualeInAttesa' => $percentualeInAttesa,
            'percentualeInAttesaConRinnovo' => $percentualeInAttesaConRinnovo,
            'percentualeVerifica' => $percentualeVerifica,
            'percentualeChiuse' => $percentualeChiuse,
            'percentualeChiuseConRinnovo' => $percentualeChiuseConRinnovo,

        ]);
    }
}
