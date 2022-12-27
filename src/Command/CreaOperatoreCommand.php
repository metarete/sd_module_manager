<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:crea-operatore',
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
            ->setHelp('Questo comando serve a creare un utente admin o user. Inserire in questo ordine i parametri: nome cognome ruolo(scegliere tra ROLE_USER e ROLE_ADMIN) email password e username.')
            ->addArgument('nome', InputArgument::REQUIRED, 'nome')
            ->addArgument('cognome', InputArgument::REQUIRED, 'cognome')
            ->addArgument('role',InputArgument::REQUIRED,'ruolo = scegli tra ROLE_USER e ROLE_ADMIN' )
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addArgument('password', InputArgument::REQUIRED, 'password')
            ->addArgument('username',InputArgument::REQUIRED, 'username = scegli un username unico' )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $em = $this->entityManager;
        $userRepository = $em ->getRepository(User::class);
        $user = new User();
        $nome = $input->getArgument('nome');
        $cognome = $input->getArgument('cognome');
        $password = $input->getArgument('password');
        $role[0] = $input->getArgument('role');
        $isVerified = true;
        $email = $input->getArgument('email');
        $username = $input->getArgument('username');

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $password
        );

        $user -> setName($nome);
        $user -> setSurname($cognome);
        $user -> setPassword($hashedPassword);
        $user -> setRoles($role);
        $user -> setIsVerified($isVerified);
        $user -> setEmail($email);
        $user -> setUsername($username);
        $userRepository->add($user, true);
        
        $output->writeln('Nome: '.$input->getArgument('nome'));
        $output->writeln('Cognome: '.$input->getArgument('cognome'));
        $output->writeln('Ruolo: '.$input->getArgument('role'));
        $output->writeln('Email: '.$input->getArgument('email'));
        $output->writeln('Username: '.$input->getArgument('username'));
        $io->success('Operatore creato con successo');

        return Command::SUCCESS;
    }
}
