<?php

namespace App\Command;

use App\Service\SDManagerClientApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:scarica-assistiti',
    description: 'comando che scarica tramite API gli assistiti da SD manager',
)]
class ScaricaAssistitiCommand extends Command
{
    private $sdManagerClientApiService;

    public function __construct(SDManagerClientApiService $sDManagerClientApiService)
    {
        $this->sdManagerClientApiService = $sDManagerClientApiService;

        parent::__construct();

    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $this->sdManagerClientApiService->sincAssistiti();

        $io->success('Assistiti aggiornati con successo.');

        return Command::SUCCESS;
    }
}