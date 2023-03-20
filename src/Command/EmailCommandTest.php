<?php

namespace App\Command;


use App\Service\MailerGeneratorTest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


#[AsCommand(
    name: 'app:email:test',
    description: 'comando di test che verifica il funzionamento del mailer con una mail di prova',
)]
class EmailCommandTest extends Command
{
    private $mailer;
    protected static $defaultDescription = 'Comando di invio mail di test.';

    public function __construct(MailerGeneratorTest $mailer)
    {
        $this->mailer = $mailer;
        parent::__construct();

    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a inviare una mail di test per verificare il funzionamento del mailer. Inserire la mail del destinatario di test come parametro')
            ->addArgument('mail', InputArgument::REQUIRED, 'Recipient mail')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $destinatarioTest = $input->getArgument('mail');
        $this->mailer->EmailTest($destinatarioTest);
        $io->success('Test completato con successo');

        return Command::SUCCESS;

    }
}