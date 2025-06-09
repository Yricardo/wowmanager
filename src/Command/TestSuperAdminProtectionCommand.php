<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// todo refactor into unit test
#[AsCommand(
    name: 'powtato:superadminprotectiontest',
    description: 'Test super admin protection by attempting to create a second super admin directly via Doctrine'
)]
class TestSuperAdminProtectionCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Testing super admin protection...</info>');

        try {
            // Try to create a second super admin directly, bypassing UserManager
            $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'testpassword');

            $user->setUsername('hackersuperadmin')
                ->setPassword($hashedPassword)
                ->setRoles(['ROLE_USER', 'ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_MEMBER'])
                ->setTrustScore(100)
                ->setCreatedAt(new \DateTimeImmutable());

            $output->writeln('<info>Attempting to persist second super admin...</info>');

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $output->writeln('<error>❌ SECURITY BREACH: Second super admin was created!</error>');

            return Command::FAILURE;
        } catch (\Exception $e) {
            $output->writeln('<success>✅ Protection working: '.$e->getMessage().'</success>');

            return Command::SUCCESS;
        }
    }
}
