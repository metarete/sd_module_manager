<?php

namespace App\Command;

use App\Entity\TipiAdiweb;
use App\Repository\TipiAdiwebRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'app:inserisci-lista-tipi-valutazione-professionale',
    description: 'comando che inserisce nel sistema la lista dei tipi nella scheda valutazione professionale',
)]
class InserisciListaTipiValutazioneProfessionaleCommand extends Command
{
    protected static $defaultDescription = 'Comando di inserimento tipi valutazione professionale';
    protected $tipiAdiwebRepository;

    public function __construct(TipiAdiwebRepository $tipiAdiwebRepository)
    {
        $this->tipiAdiwebRepository = $tipiAdiwebRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a inserire nel sistema la lista dei tipi valutazione figura professionale da file yaml');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $prova = Yaml::parseFile('config/lista_tipi.yaml');
        foreach ($prova['prestazioni_adiweb'] as $key => $value) {
            for ($i = 0; $i < count($value); $i++) {
                $tipoAdiweb = new TipiAdiweb();
                $tipoAdiweb->setNome($value[$i]['nome']);
                $tipoAdiweb->setDescrizione($value[$i]['descrizione']);
                $tipoAdiweb->setCodice($value[$i]['codice']);
                $tipoAdiweb->setAdiwebIdPrestazione($value[$i]['adiweb_id_prestazione']);
                $tipoAdiweb->setTipoOperatore($key);
                if ($this->tipiAdiwebRepository->findBy(['codice' => $tipoAdiweb->getCodice()]) == null) {
                    $this->tipiAdiwebRepository->add($tipoAdiweb, true);
                }
            }
        }

        $io->success('Comando completato con successo');

        return Command::SUCCESS;
    }
}
