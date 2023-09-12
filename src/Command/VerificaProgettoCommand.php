<?php

namespace App\Command;

use App\Entity\EntityPAI\SchedaPAI;
use App\Service\SetterStatoVerificaSchedaPaiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:verifica',
    description: 'comando che controlla se i progetti scadono tra 7 giorni e li setta nello stato verifica',
)]
class VerificaProgettoCommand extends Command
{
    private $entityManager;
    private $setterStatoVerificaSchedaPaiService;
    protected static $defaultDescription = 'Verifica dei progetti.';
    
    public function __construct(EntityManagerInterface $entityManager, SetterStatoVerificaSchedaPaiService $setterStatoVerificaSchedaPaiService)
    {
        $this->entityManager = $entityManager;
        $this->setterStatoVerificaSchedaPaiService = $setterStatoVerificaSchedaPaiService;

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
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        
        $schedaPais = $schedaPAIRepository->findBy([]);
        for($i =0; $i<count($schedaPais); $i++){
            $this->setterStatoVerificaSchedaPaiService->settaStatoVerifica($schedaPais[$i]);
        }
        
        $this->entityManager->flush();

        $io->success('Comando completato. Tutte le schede che hanno la scadenza fino a 7 giorni  sono in stato verifica.');

        return Command::SUCCESS;
    }
}