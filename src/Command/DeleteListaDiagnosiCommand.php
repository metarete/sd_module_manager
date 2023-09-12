<?php

namespace App\Command;

use App\Repository\DiagnosiRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:elimina-lista-diagnosi',
    description: 'comando che elimina dal sistema la lista delle diagnosi',
)]
class DeleteListaDiagnosiCommand extends Command
{
    protected static $defaultDescription = 'Comando di eliminazione lista diagnosi';
    private $diagnosiRepository;

    public function __construct(DiagnosiRepository $diagnosiRepository)
    {
        $this->diagnosiRepository = $diagnosiRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a eliminare dal sistema la lista delle diagnosi');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $this->diagnosiRepository->deleteAll();
        
        $io->success('Comando completato con successo');

        return Command::SUCCESS;
    }
}