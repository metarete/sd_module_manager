<?php

namespace App\Command;

use App\Entity\EntityPAI\ChiusuraServizio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:motivazioni-chiusura',
    description: 'comando che setta in tutte le chiusure servizi vecchie le motivazioni in altro',
)]
class MotivazioniChiusuraCommand extends Command
{
    private $entityManager;
    protected static $defaultDescription = 'Setting motivazioni in altro.';
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a impostare in tutte le chiusure servizi vecchie le motivazioni in altro');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $chiusuraServizioRepository = $this->entityManager->getRepository(ChiusuraServizio::class);
        
        $chiusureServizi = $chiusuraServizioRepository->findBy([]);
        for($i =0; $i<count($chiusureServizi); $i++){
            $chiusureServizi[$i]->setMotivazioneChiusura('Altro');
        }
        
        $this->entityManager->flush();

        $io->success('Comando completato.');

        return Command::SUCCESS;
    }
}