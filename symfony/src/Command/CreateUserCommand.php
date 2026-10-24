<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(name: 'app:user:create', description: 'Створити користувача з роллю client, manager або admin')]
final class CreateUserCommand extends Command
{
    private const ROLE_MAP = [
        'client' => User::ROLE_CLIENT,
        'manager' => User::ROLE_MANAGER,
        'admin' => User::ROLE_ADMIN,
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ValidatorInterface $validator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Пароль')
            ->addArgument('fullName', InputArgument::REQUIRED, "Повне ім'я")
            ->addArgument('role', InputArgument::OPTIONAL, 'client, manager або admin', 'client')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $roleKey = strtolower((string) $input->getArgument('role'));

        if (!isset(self::ROLE_MAP[$roleKey])) {
            $io->error('Невідома роль. Допустимі значення: client, manager, admin.');

            return Command::INVALID;
        }

        $email = (string) $input->getArgument('email');
        if (null !== $this->userRepository->findOneByEmail($email)) {
            $io->error(\sprintf('Користувач %s вже існує.', $email));

            return Command::FAILURE;
        }

        $user = (new User())
            ->setEmail($email)
            ->setFullName((string) $input->getArgument('fullName'))
            ->setRole(self::ROLE_MAP[$roleKey]);
        $user->setPassword($this->passwordHasher->hashPassword($user, (string) $input->getArgument('password')));

        $violations = $this->validator->validate($user);
        if (\count($violations) > 0) {
            foreach ($violations as $violation) {
                $io->error($violation->getPropertyPath().': '.$violation->getMessage());
            }

            return Command::FAILURE;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(\sprintf('Користувача %s (%s) створено.', $user->getEmail(), $user->getRole()));

        return Command::SUCCESS;
    }
}
