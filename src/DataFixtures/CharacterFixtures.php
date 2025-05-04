<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Character;
use App\DataFixtures\CharacterClassFixtures;
use App\DataFixtures\CharacterRoleFixtures;
use App\DataFixtures\ServerFixtures;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\WowVersionFixtures;
use App\Entity\CharacterClass;
use	App\Entity\Server;
use App\Entity\WowVersion;
use	App\Entity\User;

class CharacterFixtures extends Fixture implements DependentFixtureInterface
{

	public function load(ObjectManager $manager): void
	{
		$classesNames = [
            'warrior',
            'paladin',
            'hunter',
            'rogue',
            'priest',
            'death knight',
            'shaman',
            'mage',
            'warlock'
        ];

		for($i = 1; $i <= 20; $i++)
		{
			$max = rand(2, 4);
			$user = $this->getReference('f_user' . $i, User::class);
			for($j = 1; $j <= $max; $j++)
			{
				$char = $this->generateCharacter('fcharacter_' . $i . '_' . $j, $user);
				$manager->persist($char);
			}
		}

		for($i = 1; $i <= 20; $i++)
		{
			$user = $this->getReference('s_user' . $i, User::class);
			$char = $this->generateCharacter('scharacter_'.$i, $user);
			$manager->persist($char);
		}

		$manager->flush();
	}

	private function generateCharacter(string $referenceName, User $owner): Character
	{
		$classesNames = [
            'warrior',
            'paladin',
            'hunter',
            'rogue',
            'priest',
            'death knight',
            'shaman',
            'mage',
            'warlock'
        ];

		$char = new Character();
		$char->setUser($owner);
		$char->setName(uniqid());

		$wowVersion = rand(0,1) ? WowVersion::WOW_VERSION_CLASSIC : WowVersion::WOW_VERSION_RETAIL;
		$maxLevel = 50;
		if($wowVersion === WowVersion::WOW_VERSION_CLASSIC)
		{
			$maxLevel = 85;
		}
		//we referenced 1 server per version so just have to pick the good reference for the good version.
		$char->setServer($this->getReference($wowVersion, Server::class));
		$char->setLevel($maxLevel);
		$char->setGearLevel(round(390 * ($char->getLevel() / $maxLevel)));
		$class = $this->getReference('class_' . $classesNames[array_rand($classesNames)], CharacterClass::class);
		$char->setCharacterClass($class);

		$this->addReference($referenceName, $char);

		return $char;
	}

	public function getDependencies(): array
	{
		return [
			UserFixtures::class,
			ServerFixtures::class,
			CharacterRoleFixtures::class,
			CharacterClassFixtures::class,
			WowVersionFixtures::class
		];
	}

}