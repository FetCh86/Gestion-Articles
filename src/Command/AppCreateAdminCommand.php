<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppCreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    private $entityManager;
    private $encoder;

    public function __construct(EntityManagerInterface $entityManager , UserPasswordEncoderInterface $encoder )
    {
        $this->entityManager = $entityManager ;
        $this->encoder = $encoder ;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creation d\'un compte d\'administrateur.')
            ->addArgument('nickname', InputArgument::REQUIRED, 'Pseudo pour le compte administrateur.')
            ->addArgument('email', InputArgument::REQUIRED, 'Email pour le compte administrateur.')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe pour le compte administrateur.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle( $input, $output);
        $nickname = $input->getArgument('nickname');
        $email = $input->getArgument( 'email');
        $password = $input->getArgument( 'password');
        $io->note(sprintf('Creation d\'un admin avec cette email: %s', $email));
        $user = new Utilisateur();
        $user->setNickname('ADMIN_' . $nickname);
        $user->setEmail( $email);
        $passwordEncoded = $this->encoder->encodePassword( $user, $password);
        $user->setPassword( $passwordEncoded );
        $user->setRoles([ 'ROLE_ADMIN','ROLE_USER']);
        $this->entityManager->persist( $user);
        $this->entityManager->flush();
        $io->success( sprintf('Administrateur créé avec cette e-mail: %s', $email));
    }
}
