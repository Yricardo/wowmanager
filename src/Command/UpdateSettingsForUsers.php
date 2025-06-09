<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\User;
use App\Helper\SettingHelper;
use App\Repository\UserRepository;
use App\Manager\SettingManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

//todo refactor into unit test
#[AsCommand(
    name: 'powtato:update-settings-for-users',
    description: 'Update settings for all users to ensure they have the latest configuration.'
)]
class UpdateSettingsForUsers extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SettingManager $settingManager,
        private UserRepository $userRepository,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Updating settings for all users...</info>');

        foreach (SettingHelper::getSupportedUserSettingsNames() as $settingName) {
            try {
                $output->writeln('<info>Processing setting: ' . $settingName . '</info>');
                $this->createUserSettingEntriesIfMissing($settingName, $output);
            } catch (\Exception $e) {
                $output->writeln('<error>Error processing setting ' . $settingName . ': ' . $e->getMessage() . '</error>');
                continue; // Skip to the next setting if there's an error
            }
        }

        $output->writeln('<info>All user settings updated successfully!</info>');
        return Command::SUCCESS;
    }

    /**
     * IMPORTANT When introducing a new user setting, make sure you have added const and properf mapping for it in SettingHelper
     * this method, to be called in a command when updating wowmanager, will create setting entries for all existing users
     *
     * @param User $supposedSuperAdmin The super admin user
     * @param string $name Setting name
     * @param string $type Setting type
     * @throws \Exception When user is not super admin or setting is invalid
     */
    public function createUserSettingEntriesIfMissing(
        string $name,
        OutputInterface $output
    ): void {
        if (!SettingHelper::isSettingDefined($name)) {
            throw new \Exception('Invalid setting name passed: ' . $name);
        }
        $mapping = SettingHelper::getNameValueTypesAndDefaultValueMapping();
        if (!isset($mapping[$name])) {
            throw new \Exception('<error>Missing mapping for ' . $name . '</error>');
        }
        $users = $this->userRepository->findAll();
        foreach ($users as $user)
        {
            try{
                if ($this->settingManager->getSettingValueForUser($name, $user) === null) {
                    $this->settingManager->addUserSettingEntry(
                        $name,
                        $mapping[$name]['defaultValue'],
                        $mapping[$name]['type'],
                        $user
                    );
                }
                else {
                    $output->writeln('<info>nothing to process for setting: ' . $name . '</info>');
                    break;
                }
            }catch (\Exception $e) {
                //todo log error
                $this->logger->error('Error while creating setting ' . $name . 'entry for user ' . $user->getUsername(), [
                    'error' => $e->getMessage(),
                    'user' => $user->getId()
                ]);
                continue;
            }
        }
    }
}
