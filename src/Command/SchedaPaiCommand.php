<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\EntityPAI\SchedaPAI;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:scheda-pai',
    description: 'Simulazione creazione scheda pai da SD-Manager',
)]
class SchedaPaiCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }
    


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $schedaPai = new SchedaPAI;
        $em = $this->entityManager;
        $schedaPAIRepository = $em->getRepository(SchedaPAI::class);
        $userRepository = $em ->getRepository(User::class);

        $oggi = new DateTime('now');
        $fine = new DateTime('2023-10-24');
        $schedaPai->setDataInizio($oggi);
        $schedaPai->setDataFine($fine);

        $randomId = rand(1,6);
        $utente = $userRepository->findOneBySomeField($randomId);
        $schedaPai->setIdOperatorePrincipale($utente);

        $randomId = rand(1,6);
        $utente = $userRepository->findOneBySomeField($randomId);
        $schedaPai->addidOperatoreSecondarioInf($utente);

        $randomId = rand(1,6);
        $utente = $userRepository->findOneBySomeField($randomId);
        $schedaPai->addidOperatoreSecondarioTdr($utente);

        $randomId = rand(1,6);
        $utente = $userRepository->findOneBySomeField($randomId);
        $schedaPai->addidOperatoreSecondarioLog($utente);

        $randomId = rand(1,6);
        $utente = $userRepository->findOneBySomeField($randomId);
        $schedaPai->addidOperatoreSecondarioAsa($utente);

        $randomId = rand(1,6);
        $utente = $userRepository->findOneBySomeField($randomId);
        $schedaPai->addidOperatoreSecondarioOss($utente);

        $schedaPai->setIdAssistito(rand(1,100));
        $schedaPai->setIdConsole('demo');
        $schedaPai->setIdProgetto(rand(1,100));
        $schedaPai->setCurrentPlace('nuova');

        $schedaPAIRepository->add($schedaPai, true);

       

        $io->success('Simulazione completata. Nuova scheda creata');

        return Command::SUCCESS;
    }
}
