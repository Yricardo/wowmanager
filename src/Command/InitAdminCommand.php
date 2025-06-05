<?php 
// src/Command/InitAdminCommand.php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Managers\SettingManager;
use App\Managers\UserManager;
use App\Helper\SettingHelper;
use Symfony\Component\Console\Input\InputArgument;
use Psr\Log\LoggerInterface;

#[AsCommand(name: 'powtato:base:launch', description: 'Initialize the base settings and create the super admin user')]
class InitAdminCommand extends Command
{    /**
     * Constructor with dependency injection for gaming platform initialization
     * 
     * @param UserManager $userManager Manager for user operations
     * @param SettingManager $settingManager Manager for system settings
     * @param SettingHelper $settingHelper Helper for setting operations
     * @param LoggerInterface $logger Logger for critical operations
     */
    public function __construct(
        private UserManager $userManager,
        private SettingManager $settingManager,
        private SettingHelper $settingHelper,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }   
    /**
     * @throws \Exception When user creation or settings generation fails
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>🚀 Initializing WoW Manager base settings and super admin...</info>');
        
        try {
            $username = $input->getArgument('username');
            $password = $input->getArgument('password');
            
            $output->writeln("<info>Creating super admin user: {$username}</info>");
              $superAdmin = $this->userManager->addSuperAdmin(
                $username, 
                $password
            );
            
            $output->writeln('<info>Generating base system settings...</info>');
            $this->settingManager->generateBaseSettings($superAdmin);
            
            $output->writeln('<success>✅ WoW Manager initialization completed successfully!</success>');
            $this->logger->info('WoW Manager base initialization completed', [
                'super_admin_username' => $username,
                'user_id' => $superAdmin->getId()
            ]);
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->logger->error('CRITICAL! Failed to initiate super admin and global settings!', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $output->writeln('<error>❌ Failed to create base settings and admin user: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }

    /**
     * Configure command arguments for WoW Manager initialization
     * 
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('username', InputArgument::REQUIRED, 'The username of the super admin user');
        $this->addArgument('password', InputArgument::REQUIRED, 'The password of the super admin user');
    }
}