<?php

namespace App\Managers;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Setting;
use App\Entity\User;
use App\Repository\SettingRepository;
use App\Form\Settings\SettingFormHelper;
use App\Form\DataObject\Settings\SettingsDataObject;

class SettingManager
{

    public const SETTING_BORDERS_COLOR = 'bordersColor';
    public const SETTING_TOGGLE_FEED_DEFAULT = 'toggleFeedDefault';
    public const SETTING_PUBLIC_PROFILE = 'publicProfile';
    public const SETTING_ACCEPT_DM_FROM_GUILDEE = 'acceptDmFromGuildee';

    public function __construct(
        private SettingRepository $repository
    ) 
    {}

    public function getSettings(User $user): array
    {
        $supportedSettingsNames = self::getSupportedSettingsNames();
        $settings = $this->repository->findBy(['user' => $user]);

        return \array_filter($settings, function($setting) use ($supportedSettingsNames){
            return \in_array($setting->getName(), $supportedSettingsNames);
        });
    }

    public function getSettingValue(User $user,string $name): mixed
    {
        if (!\in_array($name, self::getSupportedSettingsNames()))
            throw new Exception('unsupported setting (name not found)');

        $setting = $this->repository->findBy(['name' => $name, 'user' => $user]);

        if (!$setting)
            throw new Exception('setting ' . $setting->getName() . ' not found');

        return self::formatValue($setting);
    }

    public function addSetting(string $name, mixed $value, string $type) : ?Setting
    {
        //check that name is unique 

        //check that type is allowed

        //check value integrity (class and instance has to exist if type is entity, no string in int, must be castable to string...)

        //proceed

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
            $this->repository->updateSettingValueByName($currentUser, $settingName, $value);
        }
    }

    public static function getAllowedSettingTypes(): array
    {
        return [
            Setting::SETTING_TYPE_STRING,
            Setting::SETTING_TYPE_INT,
            Setting::SETTING_TYPE_FLOAT,
            Setting::SETTING_TYPE_BOOL,
            Setting::SETTING_TYPE_ENTITY//todo implement
        ];
    }

    public static function getSupportedSettingsNames(): array
    {
        return [
            self::SETTING_BORDERS_COLOR,
            self::SETTING_TOGGLE_FEED_DEFAULT,
            self::SETTING_PUBLIC_PROFILE,
            self::SETTING_ACCEPT_DM_FROM_GUILDEE
        ];
    }

    public static function formatValue(Setting $setting): mixed 
    {
        switch($setting->getType()){
            case Setting::SETTING_TYPE_INT :
                return (int)$setting->getValue();
            case Setting::SETTING_TYPE_FLOAT :
                return (float)$setting->getValue();
            case Setting::SETTING_TYPE_BOOL :
                return (bool)$setting->getValue();
            case Setting::SETTING_TYPE_STRING :
                return (string)$setting->getValue();
            case Setting::SETTING_TYPE_ENTITY :
                return (string)$setting->getValue();//todo implement
            default:
                throw new Exception('unsupported field type ' . $setting->getType());
        }
        return $value;
    }
}