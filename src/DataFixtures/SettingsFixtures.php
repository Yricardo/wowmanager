<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Setting;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SettingsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        //            $this->addReference('f_user' . $i, $user);
        //             $users[] = $this->getReference('f_user' . $i, User::class);

        $users = [];

        foreach (['f_user', 's_user'] as $prefix)
        {
            for ($i = 1; $i <= 20; $i++)
            {
                $users[] = $this->getReference($prefix . $i, User::class);
            }
        }

        $settings = [
            [
                'name' => 'toggleFeedDefault',
                'type' => Setting::SETTING_TYPE_BOOL,
                'value' => '0',
            ],
            [
                'name' => 'publicProfile',
                'type' => Setting::SETTING_TYPE_BOOL,
                'value' => '1',
            ],
            [
                'name' => 'acceptDmFromGuildee',
                'type' => Setting::SETTING_TYPE_BOOL,
                'value' => '1',
            ],                              
            [
                'name' => 'bordersColor',
                'type' => Setting::SETTING_TYPE_STRING,
                'value' => 'somcode',
            ]
        ];

        foreach ($users as $user)
        {
            foreach ($settings as $settingData) {
                $setting = (new Setting())
                ->setUser($user)
                ->setName($settingData['name'])
                ->setType($settingData['type'])
                ->setValue($settingData['value']);
                $manager->persist($setting);
            }
        }

        $manager->flush();   
    }

    public function getDependencies(): array
	{
		return [
			UserFixtures::class
		];
	}    
}
