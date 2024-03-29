<?php

namespace App\Command;

use App\Service\SDManagerClientApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:scarica-progetti',
    description: 'comando che scarica tramite API i progetti da SD manager',
)]
class ScaricaProgettiCommand extends Command
{
    private $sdManagerClientApiService;
    protected static $defaultDescription = 'Scarica progetti';

    public function __construct(SDManagerClientApiService $sDManagerClientApiService)
    {
        $this->sdManagerClientApiService = $sDManagerClientApiService;
        parent::__construct();

    }
    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a scaricare tutti i progetti. ');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dataInizio = date('d-m-Y', strtotime('-3 month'));
        $dataFine = date('d-m-Y', strtotime('+3 month'));
        $this->sdManagerClientApiService->sincProgetti($dataInizio, $dataFine);
       
        $io->success('Progetti aggiornati con successo: '.$this->sdManagerClientApiService->getNumeroProgettiAggiornati());
        $io->success('Progetti nuovi scaricati con successo: ' .$this->sdManagerClientApiService->getNumeroProgettiScaricati());
        return Command::SUCCESS;
    }

    
}