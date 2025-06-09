<?php

namespace App\DataFixtures;

use App\Entity\AvailableRoleForClass;
use App\Entity\CharacterClass;
use App\Entity\CharacterRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AvailableRoleForClassFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $rolesNames = [
            'dps',
            'heal',
            'tank',
        ];

        $classesNames = [
            'warrior',
            'paladin',
            'hunter',
            'rogue',
            'priest',
            'death knight',
            'shaman',
            'mage',
            'warlock',
        ];

        for ($index = 0; $index < count($rolesNames); ++$index) {
            $roleName = $rolesNames[$index];
            $role = $this->getReference('role_'.$roleName, CharacterRole::class);
            for ($classIndex = 0; $classIndex < count($classesNames); ++$classIndex) {
                $className = $classesNames[$classIndex];
                $class = $this->getReference('class_'.$className, CharacterClass::class);
                $availableRoleForClass = new AvailableRoleForClass();
                $availableRoleForClass->setRole($role);
                $availableRoleForClass->setClass($class);
                $manager->persist($availableRoleForClass);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CharacterRoleFixtures::class,
            CharacterClassFixtures::class,
        ];
    }
}
