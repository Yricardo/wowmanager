<?php 
namespace App\Helper;

use App\Entity\Setting;

class SettingHelper {

    //here add user proper setting const (dont forget to update getSupported and deployment command/snippet...)
    public const USER_SETTING_BORDERS_COLOR = 'bordersColor';
    public const USER_SETTING_TOGGLE_FEED_DEFAULT = 'toggleFeedDefault';
    public const USER_SETTING_PUBLIC_PROFILE = 'publicProfile';
    public const USER_SETTING_ACCEPT_DM_FROM_GUILDEE = 'acceptDmFromGuildee';    
    public const USER_SETTING_MESSAGES_TTL = 'messagesTtl';
    //here add global settings const (exists only from admin and superadmin user) (dont forget to update getSupported and deployment command/snippet...)
    public const GLOBAL_SETTING_OVERRIDE_BORDERS_COLOR = 'overrideBorderColor';
    public const GLOBAL_SETTING_OVERRIDE_MESSAGES_TTL = 'overrideMessagesTtl';

    public const SETTING_TYPE_STRING = 'string';
    public const SETTING_TYPE_INT = 'int';
    public const SETTING_TYPE_FLOAT = 'float';
    public const SETTING_TYPE_BOOL = 'bool';
    public const SETTING_TYPE_ENTITY = 'entity';   

    public static function getSupportedSettingsNames(): array
    {
        return \array_unique(
            \array_merge(
                self::getSupportedGlobalSettingsNames(), 
                self::getSupportedUserSettingsNames()
            )
        );
    }    
    
    /**
     * Check if a setting name is defined in supported settings
     * 
     * @param string $name The setting name to check
     * @return bool True if setting is defined, false otherwise
     */
    public static function isSettingDefined(string $name): bool 
    {
        return \in_array(
            $name,
            self::getSupportedSettingsNames()
        );
    }
    public static function getSupportedUserSettingsNames(): array 
    {
        $mapping = self::getNameValueTypesAndDefaultValueMapping();
        return \array_keys(\array_filter($mapping, function($settingDefinition) {
            return !$settingDefinition['isGlobal'];
        }));
    }

    public static function getSupportedGlobalSettingsNames(): array 
    {
        $mapping = self::getNameValueTypesAndDefaultValueMapping();
        return \array_keys(\array_filter($mapping, function($settingDefinition) {
            return $settingDefinition['isGlobal'];
        }));
    }

    public static function getAllowedSettingTypes(): array
    {
        return [
            self::SETTING_TYPE_STRING,
            self::SETTING_TYPE_INT,
            self::SETTING_TYPE_FLOAT,
            self::SETTING_TYPE_BOOL,
            self::SETTING_TYPE_ENTITY
        ];
    }

    public static function formatValue(Setting $setting): mixed 
    {
        switch($setting->getType()){
            case self::SETTING_TYPE_INT :
                return (int)$setting->getValue();
            case self::SETTING_TYPE_FLOAT :
                return (float)$setting->getValue();
            case self::SETTING_TYPE_BOOL :
                return (bool)$setting->getValue();
            case self::SETTING_TYPE_STRING :
                return (string)$setting->getValue();
            case self::SETTING_TYPE_ENTITY :
                return (string)$setting->getValue();//todo implement
            default:
                throw new \Exception('unsupported field type ' . $setting->getType());
        }
        return $value;
    }
      public static function getNameValueTypesAndDefaultValueMapping(): array
    {
        return [
            self::USER_SETTING_BORDERS_COLOR => [
                'type' => Setting::SETTING_TYPE_STRING, 
                'defaultValue' => '', 
                'name' => 'userBordersColor',
                'isGlobal' => false
            ],
            self::USER_SETTING_TOGGLE_FEED_DEFAULT => [
                'type' => Setting::SETTING_TYPE_BOOL, 
                'defaultValue' => true, 
                'name' => 'toggleFeedDefault',
                'isGlobal' => false
            ],
            self::USER_SETTING_PUBLIC_PROFILE => [
                'type' => Setting::SETTING_TYPE_BOOL, 
                'defaultValue' => false, 
                'name' => "publicProfile",
                'isGlobal' => false
            ],
            self::USER_SETTING_ACCEPT_DM_FROM_GUILDEE => [
                'type' => Setting::SETTING_TYPE_STRING, 
                'defaultValue' => true, 
                'name' => 'acceptDmFromGuildee',
                'isGlobal' => false
            ],
            self::USER_SETTING_MESSAGES_TTL => [
                'type' => Setting::SETTING_TYPE_STRING, 
                'defaultValue' => '2', 
                'name' => 'messagesTtl',
                'isGlobal' => false
            ],
            self::GLOBAL_SETTING_OVERRIDE_BORDERS_COLOR => [
                'type' => Setting::SETTING_TYPE_STRING, 
                'defaultValue' => '', 
                'name' => 'overrideBordersColor',
                'isGlobal' => true
            ],
            self::GLOBAL_SETTING_OVERRIDE_MESSAGES_TTL => [
                'type' => Setting::SETTING_TYPE_STRING, 
                'defaultValue' => '2', 
                'name' => 'overrideMessagesTtl',
                'isGlobal' => true
            ]
        ];
    }
}