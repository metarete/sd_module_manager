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


#[AsCommand(
    name: 'app:chiudi-progetto',
    
)]
class ChiudiProgettoCommand extends Command
{
    private $entityManager;
    protected static $defaultDescription = 'Chiusura dei progetti.';
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a impostare lo stato delle schede in attesa di chiusura se hanno superato la data di scadenza.');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $em = $this->entityManager;
        $dataOggi = new DateTime('now');
        $schedaPAIRepository = $em->getRepository(SchedaPAI::class);
        $schedaPais = $schedaPAIRepository->findBy(['dataFine' =>$dataOggi, 'currentPlace' => 'attiva']);

        for($i =0; $i<count($schedaPais); $i++)
        $schedaPais[$i]->setCurrentPlace('in_attesa_di_chiusura');
        
        $em->flush();



        $io->success('Comando completato. Tutte le schede che hanno superato la data di chiusura sono in stato attesa di chiusura.');

        return Command::SUCCESS;
    }
}