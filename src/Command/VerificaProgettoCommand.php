<?php

namespace App\Command;

use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DateTime;
use DateInterval;


#[AsCommand(
    name: 'app:verifica',
    description: 'comando che controlla se i progetti scadono tra 7 giorni e li setta nello stato verifica',
)]
class VerificaProgettoCommand extends Command
{
    private $entityManager;
    protected static $defaultDescription = 'Verifica dei progetti.';
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a impostare lo stato delle schede in verifica se tra 7 giorni scadono.');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dataOggi = new DateTime('now');
        $interval = new DateInterval('P6D');
        $dataDiCheck = $dataOggi->add($interval);
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPais = $schedaPAIRepository->findBy(['dataFine' =>$dataDiCheck, 'currentPlace' => 'attiva']);

        for($i =0; $i<count($schedaPais); $i++)
        $schedaPais[$i]->setCurrentPlace('verifica');
        
        $this->entityManager->flush();



        $io->success('Comando completato. Tutte le schede che hanno la scadenza tra 7 giorni sono in stato verifica.');

        return Command::SUCCESS;
    }
}