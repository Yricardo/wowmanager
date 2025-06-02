<?php

namespace App\Managers;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Setting;
use App\Entity\User;
use App\Repository\SettingRepository;
use App\Form\Settings\SettingFormHelper;
use App\Form\DataObject\Settings\SettingsDataObject;
use App\Helper\SettingHelper;

class SettingManager
{
    public function __construct(
        private SettingRepository $repository,
        private EntityManagerInterface $entityManager
    ) 
    {}/**
     * Get settings for a user based on their role
     * 
     * @param User $user The user to get settings for
     * @return array Array of Setting entities
     */
    public function getSettings(User $user): array
    {
        $supportedSettingsNames = \in_array('ROLE_ADMIN', $user->getRoles()) 
            ? SettingHelper::getSupportedSettingsNames() 
            : SettingHelper::getSupportedUserSettingsNames();
        
        $settings = $this->repository->findBy(['user' => $user]);
        
        return \array_filter($settings, function($setting) use ($supportedSettingsNames){
            return \in_array($setting->getName(), $supportedSettingsNames);
        });
    }    /**
     * Get setting value for a specific user
     * 
     * @param string $name Setting name
     * @param User $user The user
     * @return mixed The formatted setting value or null if not found
     */
    public function getSettingValueForUser(string $name, User $user): mixed
    {
        if (!SettingHelper::isSettingDefined($name)) {
            // TODO: log error trying to access undefined setting
            return null;
        }
        
        $setting = $this->repository->findOneBy(['name' => $name, 'user' => $user]);
        if (!$setting) {
            return null;
        }
        
        return SettingHelper::formatValue($setting);        
    }/**
     * Generate default settings for a user
     * 
     * @param User $user The user to generate settings for
     * @throws \Exception When setting creation fails
     */
    public function generateSettingsForUser(User $user): void
    {
        $mapping = SettingHelper::getNameValueTypesAndDefaultValueMapping();
        
        foreach (SettingHelper::getSupportedUserSettingsNames() as $settingName) {
            $settingDefinition = $mapping[$settingName];

            $this->addSettingEntry(
                $settingName, 
                $settingDefinition['defaultValue'], 
                $settingDefinition['type'], 
                $user, 
                false
            );
        }
    }    /**
     * Generate global system settings (super admin only)
     * 
     * @param User $supposedSuperAdmin The super admin user
     * @throws \Exception When user is not super admin or setting creation fails
     */
    public function generateBaseSettings(User $supposedSuperAdmin): void
    {
        $mapping = SettingHelper::getNameValueTypesAndDefaultValueMapping();
        
        foreach (SettingHelper::getSupportedGlobalSettingsNames() as $settingName) {
            $this->addGlobalSettingEntry($supposedSuperAdmin, $settingName, $mapping[$settingName]['type']);
        }
    }    /**
     * Add global setting entry (super admin only)
     * 
     * @param User $supposedSuperAdmin The super admin user
     * @param string $name Setting name
     * @param string $type Setting type
     * @throws \Exception When user is not super admin or setting is invalid
     */
    private function addGlobalSettingEntry(User $supposedSuperAdmin, string $name, string $type): void
    {
        if (!\in_array('ROLE_SUPER_ADMIN', $supposedSuperAdmin->getRoles())) {
            throw new \Exception('Only super admin can add global setting entry');
        }
        
        if (!\in_array($name, SettingHelper::getSupportedGlobalSettingsNames())) {
            throw new \Exception('Invalid setting name passed: ' . $name);
        }
        
        $mapping = SettingHelper::getNameValueTypesAndDefaultValueMapping();
        if (!isset($mapping[$name])) {
            throw new \Exception('Missing mapping for ' . $name);
        }
        
        $this->addSettingEntry(
            $name, 
            $mapping[$name]['defaultValue'], 
            $mapping[$name]['type'], 
            $supposedSuperAdmin, 
            true
        );
    }
   
    public function updateSettingValue(string $name, string $value)
    {
        //check value integrity
        //proceed
    }

    public function updateSettingsFromSettingType(User $currentUser, array $datas)
    {
        foreach ($datas as $settingName => $value)
        {
            if(!$this->isSettingAllowedFor($currentUser, $settingName))
            {
                throw new Exception('current user is not permitted to write into ' . $settingName . ' setting.');
            }
            //if conf to update is global, it is linked to super admin (there can only be one super admin and a global conf exists once).
            if(\in_array($settingName, SettingHelper::getSupportedGlobalSettingsNames()))
            {
                $currentUser = $userRepository->findByRole('superadmin')[0];
            }    
            $this->repository->updateSettingValueByName($currentUser, $settingName, $value);
        }
    }    /**
     * Check if setting is allowed for user based on their roles
     * 
     * @param User $user The user to check
     * @param string $settingName The setting name
     * @return bool True if allowed, false otherwise
     */
    public function isSettingAllowedFor(User $user, string $settingName): bool 
    {
        $userPossibleSettings = SettingHelper::getSupportedUserSettingsNames();
        
        if (\in_array('ROLE_ADMIN', $user->getRoles())) {
            $userPossibleSettings = \array_unique(\array_merge(
                $userPossibleSettings, 
                SettingHelper::getSupportedGlobalSettingsNames()
            ));
        }
        
        return \in_array($settingName, $userPossibleSettings);    
    }
    /**
     * Add setting entry to database
     * 
     * @param string $name Setting name
     * @param mixed $value Setting value
     * @param string $type Setting type
     * @param User $for User to create setting for
     * @param bool $isGlobal Whether this is a global setting
     * @return Setting The created setting entity
     * @throws \Exception When setting creation fails
     */
    private function addSettingEntry(string $name, mixed $value, string $type, User $for, bool $isGlobal = false): Setting
    {
        if (!\in_array($type, SettingHelper::getAllowedSettingTypes())) {
            throw new \Exception('Incorrect type given: ' . $type);
        }
        
        if (!$this->isSettingAllowedFor($for, $name)) {
            throw new \Exception('Cannot add ' . $name . ' setting to user ' . $for->getUsername());
        }
        
        if ($this->getSettingValueForUser($name, $for) !== null) {
            throw new \Exception('Cannot duplicate setting entry for user');
        }
        
        if ($isGlobal && !\in_array('ROLE_SUPER_ADMIN', $for->getRoles())) {
            throw new \Exception('Cannot add global setting ' . $name . ' to user ' . $for->getUsername());
        }
          $setting = (new Setting())
            ->setName($name)
            ->setValue((string)$value)
            ->setType($type)
            ->setIsGlobal($isGlobal)
            ->setUser($for);
            
        $this->entityManager->persist($setting);
        $this->entityManager->flush();
            
        return $setting;
    }
}