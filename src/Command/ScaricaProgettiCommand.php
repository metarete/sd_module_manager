<?php

namespace App\Command;

use App\Service\SDManagerClientApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:scarica-progetti',
)]
class ScaricaProgettiCommand extends Command
{
    private $sdManagerClientApiService;

    public function __construct(SDManagerClientApiService $sDManagerClientApiService)
    {
        $this->sdManagerClientApiService = $sDManagerClientApiService;
        parent::__construct();

    }
    protected function configure(): void
    {
        $this
            ->addArgument('data_inizio', InputArgument::OPTIONAL, 'data inizio')
            ->addArgument('data_fine', InputArgument::OPTIONAL, 'data fine')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dataInizio = $input->getArgument('data_inizio');
        $dataFine = $input->getArgument('data_fine');
        $this->sdManagerClientApiService->sincProgetti($dataInizio, $dataFine);
       
        
        $io->success('Evviva funziona.');
        return Command::SUCCESS;
    }

    
}