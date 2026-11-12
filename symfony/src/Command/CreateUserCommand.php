<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-user', description: 'Create a user with the given role')]
class CreateUserCommand extends Command
{

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('role', InputArgument::OPTIONAL, 'ROLE_CLIENT | ROLE_MANAGER | ROLE_ADMIN', User::ROLE_CLIENT);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $role = $input->getArgument('role');

        if (!in_array($role, User::ROLES, true)) {
            $output->writeln('<error>Role must be one of: ' . implode(', ', User::ROLES) . '</error>');

            return Command::FAILURE;
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $input->getArgument('email')]);

        if (empty($user)) {
            $user = new User();
            $user->setEmail($input->getArgument('email'));
        }

        $user
            ->setRoles([$role])
            ->setPassword($this->passwordHasher->hashPassword($user, $input->getArgument('password')));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('<info>User ' . $user->getEmail() . ' saved with role ' . $role . '</info>');

        return Command::SUCCESS;
    }

}
