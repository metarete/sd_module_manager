<?php

namespace App\Command;

use App\Repository\PresidiAntidecubitoRepository;
use App\Entity\PresidiAntidecubito;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:inserisci-lista-presidi-antidecubito',
    description: 'comando che inserisce nel sistema la lista dei presidi antidecubito',
)]
class InserisciListaPresidiAntidecubitoCommand extends Command
{

    protected static $defaultDescription = 'Comando di inserimento lista presidi antidecubito';
    private $presidiAntidecubitoRepository;

    public function __construct(PresidiAntidecubitoRepository $presidiAntidecubitoRepository)
    {
        $this->presidiAntidecubitoRepository = $presidiAntidecubitoRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a inserire nel sistema la lista dei presidi antidecubito');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $presidio = new PresidiAntidecubito;
        $presidio->setNome("materasso");
        $this->presidiAntidecubitoRepository->add($presidio, true);
        $presidio = new PresidiAntidecubito;
        $presidio->setNome("cuscino");
        $this->presidiAntidecubitoRepository->add($presidio, true);
        $presidio = new PresidiAntidecubito;
        $presidio->setNome("talloniera");
        $this->presidiAntidecubitoRepository->add($presidio, true);

        $io->success('Comando completato con successo');

        return Command::SUCCESS;
    }
}
