<?php

namespace App\Command;

use App\Entity\BordiLesione;
use App\Repository\CondizioneLesioneRepository;
use App\Entity\CondizioneLesione;
use App\Entity\CutePerilesionale;
use App\Entity\Medicazione;
use App\Entity\Copertura;
use App\Repository\BordiLesioneRepository;
use App\Repository\CutePerilesionaleRepository;
use App\Repository\MedicazioneRepository;
use App\Repository\CoperturaRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:inserisci-lista-campi-lesione',
    description: 'comando che inserisce nel sistema la lista dei campi nella scheda lesione',
)]
class InserisciListaCampiLesioneCommand extends Command
{

    protected static $defaultDescription = 'Comando di inserimento lista campi lesione';
    private $condizioneLesioneRepository;
    private $bordiLesioneRepository;
    private $cutePerilesionaleRepository;
    private $medicazioneRepository;
    private $coperturaRepository;

    public function __construct(CondizioneLesioneRepository $condizioneLesioneRepository, BordiLesioneRepository $bordiLesioneRepository, CutePerilesionaleRepository $cutePerilesionaleRepository, MedicazioneRepository $medicazioneRepository, CoperturaRepository $coperturaRepository)
    {
        $this->condizioneLesioneRepository = $condizioneLesioneRepository;
        $this->bordiLesioneRepository = $bordiLesioneRepository;
        $this->cutePerilesionaleRepository = $cutePerilesionaleRepository;
        $this->medicazioneRepository = $medicazioneRepository;
        $this->coperturaRepository = $coperturaRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a inserire nel sistema la lista delle condizioni lesione');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        //condizioni
        $listaCondizioni = [
            'Detersa', 
            'Infetta', 
            'Maleodorante',
            'Sottominata', 
            'Tramite fistolosi', 
            'Tessuto di granulazione',
            'Fibrina', 
            'Essudato', 
            'Necrosi nera / escara nera', 
            'Necrosi gialla / slough'
        ];

        for($i=0; $i< count($listaCondizioni); $i++){
            $condizioneLesione = new CondizioneLesione;
            $condizioneLesione->setNome($listaCondizioni[$i]);
            if($this->condizioneLesioneRepository->findOneBy(["nome" => $condizioneLesione->getNome()])== null){
                $this->condizioneLesioneRepository->add($condizioneLesione, true);
            }
        }

        //bordi lesione

        $listaBordi = [
            'Lineari', 
            'Macerati', 
            'Necrotici', 
            'Infetti', 
            'Frastagliati', 
            'A scalino' 
        ];

        for($i=0; $i< count($listaBordi); $i++){
            $bordiLesione = new BordiLesione;
            $bordiLesione->setNome($listaBordi[$i]);
            if($this->bordiLesioneRepository->findOneBy(["nome" => $bordiLesione->getNome()])== null)
                $this->bordiLesioneRepository->add($bordiLesione, true);
        }

        //cute perilesionale

        $listaCute = [
            'Integra', 
            'Arrossata', 
            'Macerata', 
            'Calda', 
            'Edematosa'
        ];

        for($i=0; $i< count($listaCute); $i++){
            $cuteLesione = new CutePerilesionale;
            $cuteLesione->setNome($listaCute[$i]);
            if($this->cutePerilesionaleRepository->findOneBy(["nome" => $cuteLesione->getNome()])== null)
                $this->cutePerilesionaleRepository->add($cuteLesione, true);
        }

        //medicazione

        $listaMedicazioni = [
            'Film semipermeabili',
            'Idrogeli',
            'Idrocolloidi',
            'Schiume sintetiche',
            'Alginate',
            'Cmc Sodica ed affini',
            'Acidi jaluronico (biomateriali)',
            'Medicazioni detergenti',
            'Collageni',
            'Medicazioni a bassa aderenza',
            'Carbone',
            'Altro'
        ];

        for($i=0; $i< count($listaMedicazioni); $i++){
            $medicazioneLesione = new Medicazione;
            $medicazioneLesione->setNome($listaMedicazioni[$i]);
            if($this->medicazioneRepository->findOneBy(["nome" => $medicazioneLesione->getNome()])== null)
                $this->medicazioneRepository->add($medicazioneLesione, true);
        }

        //copertura

        $listaCopertura = [
            'A piatto',
            'Fasciatura',
            'Bendaggio elastocompressivo'
        ];

        for($i=0; $i< count($listaCopertura); $i++){
            $coperturaLesione = new Copertura;
            $coperturaLesione->setNome($listaCopertura[$i]);
            if($this->coperturaRepository->findOneBy(["nome" => $coperturaLesione->getNome()])== null)
                $this->coperturaRepository->add($coperturaLesione, true);
        }
        
        $io->success('Comando completato con successo');

        return Command::SUCCESS;
    }
}