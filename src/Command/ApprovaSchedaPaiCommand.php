<?php

namespace App\Command;

use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:approva-scheda-pai',
    description: 'Simulazione approvazione da parte della regione della scheda pai',
)]
class ApprovaSchedaPaiCommand extends Command
{
    private $entityManager;
    protected static $defaultDescription = 'Approva scheda pai';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve ad approvare una scheda pai e a renderla compilabile dagli operatore coinvolti.')
            ->addArgument('id_scheda', InputArgument::REQUIRED, 'id scheda')
            
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $idScheda = $input->getArgument('id_scheda');
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedaPAIRepository->findOneBySomeField($idScheda);
        if($schedaPai->getIdOperatorePrincipale()== null)
            $io->error('Operatore principale mancante. Impossibile approvare la scheda pai.');
        else{
            $schedaPai->setCurrentPlace('approvata');
            $this->entityManager->flush();
            $io->success('Approvata scheda pai ' .$idScheda);
        }

        return Command::SUCCESS;
    }
}
