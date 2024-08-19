<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-role-to-user',
    description: 'Add a role to the user'
)]
class AddRoleToUserCommand extends Command
{
    protected static $defaultName = 'app:add-role-to-user';
    private UserRepository $userRepository; // Effacer le type si probleme
    private EntityManagerInterface $entityManager; // Effacer le type si probleme

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add a role to a user')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user')
            ->addArgument('role', InputArgument::REQUIRED, 'The role to add to the user (e.g. ROLE_ADMIN)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $io->error('User not found');
            return Command::FAILURE;
        }

        $roles = $user->getRoles();
        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $user->setRoles($roles);
            $this->entityManager->flush();

            $io->success("role $role added to user $email");
        } else {
            $io->warning("User $email already has the role $role");
        }

        return Command::SUCCESS;
    }
}