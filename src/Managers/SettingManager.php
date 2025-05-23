<?php

namespace App\Managers;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Setting;
use App\Repository\SettingRepository;
use App\Form\Settings\SettingFormHelper;

class SettingManager
{

    public const SETTING_BORDERS_COLOR = 'borders_color';
    public const SETTING_TOGGLE_FEED_DEFAULT = 'toggle_feed_default';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private SettingRepository $repository,
        private SettingFormHelper $settingFormHelper
    ) {
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

    public function updateSettingsFromDataObject(SettingsDataObject $dto)
    {
        dump($dto);
    }

    public function generateFormDataObject(SettingsDataObject $dto = null): SettingsType
    {
        $dataObject = $this->settingFormHelper->generateDto();
        return $dataObject;
    }

    public static function getAllowedSettingTypes(): array
    {
        return [
            Setting::SETTING_TYPE_STRING,
            Setting::SETTING_TYPE_INT,
            Setting::SETTING_TYPE_FLOAT,
            Setting::SETTING_TYPE_BOOL,
            Setting::ENTITY
        ];
    }

    public static function getSupportedSettingsNames(): array
    {
        return [
            self::SETTING_BORDERS_COLOR,
            self::SETTING_TOGGLE_FEED_DEFAULT
        ];
    }
}