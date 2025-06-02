<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Setting;
use App\Entity\User;
use App\Helper\SettingHelper;

/**
 * Test setting duplication protection
 * 
 * @todo Refactor into unit test
 */
#[AsCommand(
    name: 'powtatow:test:setting-protection',
    description: 'Test setting duplication protection by attempting to create duplicate settings'
)]
class TestSettingProtectionCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>🛡️ Testing Setting Duplication Protection...</info>');

        try {
            // Get the super admin user
            $superAdmin = $this->entityManager->getRepository(User::class)
                ->findOneBy(['username' => 'superadmin']);

            if (!$superAdmin) {
                $output->writeln('<error>❌ Super admin user not found. Run powtatow:base:launch first.</error>');
                return Command::FAILURE;
            }

            $output->writeln('<info>📋 Testing duplicate user setting creation...</info>');
            
            // Test 1: Try to create duplicate user setting
            $this->testDuplicateUserSetting($superAdmin, $output);

            $output->writeln('<info>📋 Testing duplicate global setting creation...</info>');
            
            // Test 2: Try to create duplicate global setting
            $this->testDuplicateGlobalSetting($superAdmin, $output);

            $output->writeln('<success>✅ All setting protection tests completed!</success>');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $output->writeln('<error>❌ Unexpected error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }

    private function testDuplicateUserSetting(User $user, OutputInterface $output): void
    {
        try {
            // Try to create a duplicate user setting that should already exist
            $duplicateSetting = new Setting();
            $duplicateSetting->setName(SettingHelper::USER_SETTING_BORDERS_COLOR);
            $duplicateSetting->setType(SettingHelper::SETTING_TYPE_STRING);
            $duplicateSetting->setValue('#ff0000'); // Different value
            $duplicateSetting->setIsGlobal(false);
            $duplicateSetting->setUser($user);

            $this->entityManager->persist($duplicateSetting);
            $this->entityManager->flush();

            $output->writeln('<error>❌ SECURITY BREACH: Duplicate user setting was created!</error>');
        } catch (\RuntimeException $e) {
            if (str_contains($e->getMessage(), 'Setting duplication protection')) {
                $output->writeln('<success>✅ User setting duplication properly prevented</success>');
                $output->writeln('<comment>   Protection message: ' . $e->getMessage() . '</comment>');
            } else {
                throw $e;
            }
        }
    }

    private function testDuplicateGlobalSetting(User $user, OutputInterface $output): void
    {
        try {
            // Try to create a duplicate global setting that should already exist
            $duplicateGlobalSetting = new Setting();
            $duplicateGlobalSetting->setName(SettingHelper::GLOBAL_SETTING_OVERRIDE_BORDERS_COLOR);
            $duplicateGlobalSetting->setType(SettingHelper::SETTING_TYPE_STRING);
            $duplicateGlobalSetting->setValue('#00ff00'); // Different value
            $duplicateGlobalSetting->setIsGlobal(true);
            $duplicateGlobalSetting->setUser($user);

            $this->entityManager->persist($duplicateGlobalSetting);
            $this->entityManager->flush();

            $output->writeln('<error>❌ SECURITY BREACH: Duplicate global setting was created!</error>');
        } catch (\RuntimeException $e) {
            if (str_contains($e->getMessage(), 'Setting duplication protection')) {
                $output->writeln('<success>✅ Global setting duplication properly prevented</success>');
                $output->writeln('<comment>   Protection message: ' . $e->getMessage() . '</comment>');
            } else {
                throw $e;
            }
        }
    }
}