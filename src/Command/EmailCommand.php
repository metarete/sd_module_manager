<?php

namespace App\Command;


use App\Service\MailerGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:email',
    description: 'comando di invio email a tutti gli operatori',
)]
class EmailCommand extends Command
{
    private $mailer;
    protected static $defaultDescription = 'Comando di invio mail agli operatori e agli admin.';

    public function __construct(MailerGenerator $mailer)
    {
        $this->mailer = $mailer;
        parent::__construct();

    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a inviare una volta al giorno le mail a tutti gli operatori con i relativi compiti da svolgere.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->mailer->EmailAdmin();
        $this->mailer->EmailOperatore();
        $io->success('Email mandate con successo a tutti gli operatori.');

        return Command::SUCCESS;

    }
}