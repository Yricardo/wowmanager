<?php

// filepath: src/Command/TestPasswordCommand.php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'powtato:test:password', description: 'Test password verification for debugging authentication')]
class TestPasswordCommand extends Command
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username to test')
            ->addArgument('password', InputArgument::REQUIRED, 'Password to test')
            ->setHelp('This command tests password verification to debug authentication issues');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $output->writeln('🔍 <info>Testing Authentication for: '.$username.'</info>');
        $output->writeln('');

        // Test 1: Find user
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            $output->writeln('<error>❌ User not found in database!</error>');

            return Command::FAILURE;
        }

        $output->writeln('✅ <info>User found:</info> '.$user->getUsername());
        $output->writeln('🔑 <info>Roles:</info> '.implode(', ', $user->getRoles()));
        $output->writeln('');

        // Test 2: Check stored password hash
        $storedHash = $user->getPassword();
        $output->writeln('🔐 <info>Stored password hash:</info>');
        $output->writeln('   '.$storedHash);
        $output->writeln('📝 <info>Hash type:</info> '.substr($storedHash, 0, 4));
        $output->writeln('📏 <info>Hash length:</info> '.strlen($storedHash).' characters');
        $output->writeln('');

        // Test 3: Password verification
        $output->writeln('🧪 <comment>Testing password verification...</comment>');

        try {
            $isValid = $this->passwordHasher->isPasswordValid($user, $password);

            if ($isValid) {
                $output->writeln('✅ <fg=green;options=bold>PASSWORD IS VALID - Authentication should work!</fg=green;options=bold>');
            } else {
                $output->writeln('❌ <fg=red;options=bold>PASSWORD IS INVALID - This is why login fails!</fg=red;options=bold>');
            }
        } catch (\Exception $e) {
            $output->writeln('💥 <error>Password verification failed with exception:</error>');
            $output->writeln('   '.$e->getMessage());

            return Command::FAILURE;
        }

        $output->writeln('');

        // Test 4: Generate new hash for comparison
        $output->writeln('🔧 <comment>Generating new hash for comparison...</comment>');

        try {
            $newHash = $this->passwordHasher->hashPassword($user, $password);
            $output->writeln('🆕 <info>New hash would be:</info>');
            $output->writeln('   '.$newHash);
            $output->writeln('📝 <info>New hash type:</info> '.substr($newHash, 0, 4));
            $output->writeln('📏 <info>New hash length:</info> '.strlen($newHash).' characters');

            // Compare hash types
            if (substr($storedHash, 0, 4) === substr($newHash, 0, 4)) {
                $output->writeln('✅ <info>Hash types match - using same algorithm</info>');
            } else {
                $output->writeln('⚠️  <comment>Hash types differ - possible algorithm mismatch</comment>');
            }
        } catch (\Exception $e) {
            $output->writeln('💥 <error>Hash generation failed with exception:</error>');
            $output->writeln('   '.$e->getMessage());
        }

        $output->writeln('');

        // Test 5: Service information
        $output->writeln('⚙️  <comment>Password hasher service information:</comment>');
        $output->writeln('   Service class: '.get_class($this->passwordHasher));
        $output->writeln('   Repository class: '.get_class($this->userRepository));

        $output->writeln('');
        $output->writeln('🎯 <info>Authentication Debug Summary:</info>');
        $output->writeln('   User exists: ✅');
        $output->writeln('   Password valid: '.($isValid ? '✅' : '❌'));
        $output->writeln('   Hash algorithm: '.substr($storedHash, 0, 4));

        if ($isValid) {
            $output->writeln('');
            $output->writeln('💡 <fg=yellow>If CLI shows password is valid but web login fails, check:</fg=yellow>');
            $output->writeln('   • CSRF token in login form');
            $output->writeln('   • Session configuration');
            $output->writeln('   • Firewall settings');
            $output->writeln('   • Browser cookies/cache');
            $output->writeln('   • Production vs dev environment differences');
        }

        return $isValid ? Command::SUCCESS : Command::FAILURE;
    }
}
