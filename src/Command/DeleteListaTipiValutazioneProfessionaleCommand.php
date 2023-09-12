<?php

namespace App\Command;

use App\Repository\TipiAdiwebRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:elimina-tipi-valutazione-professionale',
    description: 'comando che elimina dal sistema la lista dei tipi adiweb della valutazione professionale',
)]
class DeleteListaTipiValutazioneProfessionaleCommand extends Command
{
    protected static $defaultDescription = 'Comando di eliminazione lista dei tipi valutazione professionale';
    private $tipiAdiwebRepository;

    public function __construct(TipiAdiwebRepository $tipiAdiwebRepository)
    {
        $this->tipiAdiwebRepository = $tipiAdiwebRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a eliminare dal sistema la lista dei tipi valutazione professionale');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $this->tipiAdiwebRepository->deleteAll();
        
        $io->success('Comando completato con successo');

        return Command::SUCCESS;
    }
}