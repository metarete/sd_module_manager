<?php

namespace App\Command;

use App\Repository\DiagnosiRepository;
use App\Entity\Diagnosi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:inserisci-lista-diagnosi',
    description: 'comando che inserisce nel sistema la lista delle diagnosi da file',
)]
class InserisciListaDiagnosiCommand extends Command
{

    protected static $defaultDescription = 'Comando di inserimento lista diagnosi';
    private $diagnosiRepository;

    public function __construct(DiagnosiRepository $diagnosiRepository)
    {
        $this->diagnosiRepository = $diagnosiRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a inserire nel sistema la lista delle diagnosi da file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //salvo in array i due campi del file
        $row = 1;
        $array = [];
        if (($handle = fopen("/app/public/file/lista_diagnosi.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    $array[$row][$c] = $data[$c];
                }
                $row++;
            }
            fclose($handle);
        }
        //inserisco dalla posizione 2 fino alla fine i campi 
        for ($c = 2; $c <= $row-1; $c++) {
            $diagnosi = new Diagnosi();
            $diagnosi->setCodice($array[$c][0]);
            $diagnosi->setDescrizione($array[$c][1]);
            $this->diagnosiRepository->add($diagnosi, true);
        }
        
        
        $io->success('Comando completato con successo');

        return Command::SUCCESS;
    }
}
