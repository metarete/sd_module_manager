<?php

namespace App\Command;

use App\Service\SDManagerClientApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:rimuovi-assistiti',
    description: 'comando che rimuove gli assistiti non assegnati ad alcun progetto per motivi di privacy',
)]
class RimuoviAssistitiCommand extends Command
{
    private $sdManagerClientApiService;
    protected static $defaultDescription = 'Rimuovi Assistiti';

    public function __construct(SDManagerClientApiService $sDManagerClientApiService)
    {
        $this->sdManagerClientApiService = $sDManagerClientApiService;
        parent::__construct();

    }
    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a rimuovere gli assistiti non assegnati ad alcun progetto per motivi di privacy.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->sdManagerClientApiService->rimozioneAssistitiNonAssegnati();
       
        $io->success('Assistiti non assegnati rimossi con successo: ');
        return Command::SUCCESS;
    }

    
}