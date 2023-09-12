<?php

namespace App\Command;

use App\Repository\PresidiAntidecubitoRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:elimina-lista-presidi-antidecubito',
    description: 'comando che elimina dal sistema la lista dei presidi antidecubito',
)]
class DeleteListaPresidiAntidecubitoCommand extends Command
{
    protected static $defaultDescription = 'Comando di eliminazione lista dei presidi antidecubito';
    private $presidiAntidecubitoRepository;

    public function __construct(PresidiAntidecubitoRepository $presidiAntidecubitoRepository)
    {
        $this->presidiAntidecubitoRepository = $presidiAntidecubitoRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a eliminare dal sistema la lista dei presidi antidecubito');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $this->presidiAntidecubitoRepository->deleteAll();
        
        $io->success('Comando completato con successo');

        return Command::SUCCESS;
    }
}