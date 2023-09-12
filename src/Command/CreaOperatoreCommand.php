<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:crea-operatore',
    description: 'comando di creazione utente',
)]
class CreaOperatoreCommand extends Command
{
    private $entityManager;
    private $userPasswordHasher;
    protected static $defaultDescription = 'Creates a new user.';

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Questo comando serve a creare un utente admin o user. Inserire in questo ordine i parametri: nome cognome ruolo(scegliere tra ROLE_USER, ROLE_ADMIN e ROLE_SUPERADMIN) email password e username.')
            ->addArgument('nome', InputArgument::REQUIRED, 'nome')
            ->addArgument('cognome', InputArgument::REQUIRED, 'cognome')
            ->addArgument('codice fiscale',InputArgument::REQUIRED,'codice fiscale' )
            ->addArgument('role',InputArgument::REQUIRED,'ruolo = scegli tra ROLE_USER e ROLE_ADMIN' )
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addArgument('password', InputArgument::REQUIRED, 'password')
            ->addArgument('username',InputArgument::OPTIONAL, 'username = scegli un username unico' )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = new User();

        if($input->getArgument('role')== "ROLE_SUPERADMIN"){
            $role[0] = $input->getArgument('role');
            $role[1] = "ROLE_ADMIN";
        }
        else{
            $role[0] = $input->getArgument('role');
        }

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $input->getArgument('password')
        );

        $user->setName($input->getArgument('nome'));
        $user->setSurname($input->getArgument('cognome'));
        $user->setCf($input->getArgument('codice fiscale'));
        $user->setPassword($hashedPassword);
        $user->setRoles($role);
        $user->setStato(true);
        $user->setEmail($input->getArgument('email'));
        if($input->getArgument('username') != null){
            $user->setUsername($input->getArgument('username'));
        }
        
        $userRepository->add($user, true);
        
        $output->writeln('Nome: '.$input->getArgument('nome'));
        $output->writeln('Cognome: '.$input->getArgument('cognome'));
        $output->writeln('Codice Fiscale: '.$input->getArgument('codice fiscale'));
        $output->writeln('Ruolo: '.$input->getArgument('role'));
        $output->writeln('Email: '.$input->getArgument('email'));
        $output->writeln('Username: '.$input->getArgument('username'));
        $io->success('Operatore creato con successo');

        return Command::SUCCESS;
    }
}
