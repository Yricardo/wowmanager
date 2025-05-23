<?php 

namespace App\Form\DataObject\Settings;

use App\Entity\Setting;

class SettingsDataObject
{
    public function addSetting(Setting $setting, mixed $value): void
    {
        $this->writeField($setting);
    }

    public function addSettings(array $settings): void
    {
        foreach ($settings as $setting)
        {
            if (!$setting InstanceOf Setting)
            {
                continue;
            }
            if(isset($this->{$setting->name}))
                throw new Exception('settings ' . $setting->name . ' already added, settings name are supposed to be unique'); 
            $this->writeField($settings);
        }
    }   

    private function writeField(Setting $setting): void
    {
        switch($seting->getType()){
            case Setting::SETTING_TYPE_INT :
                $this->{$setting->name} = (int)$setting->value;
                break;
            case Setting::SETTING_TYPE_FLOAT :
                $this->{$setting->name} = (float)$setting->value;
                break;
            case Setting::SETTING_TYPE_BOOL :
                $this->{$setting->name} = (bool)$setting->value;
                break;
            case Setting::SETTING_TYPE_STRING :
                $this->{$setting->name} = (string)$setting->value;
                break;
            case Setting::SETTING_TYPE_ENTITY :
                $this->{$setting->name} = (string)$setting->value;
                break;
            default:
                throw new Exception('unsupported field type');
        }
    }
}