<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CharacterRole;
use App\Entity\AvailableRoleForClass;
use App\Entity\CharacterClass;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AvailableRoleForClassFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $rolesNames = [
            'dps',
            'heal',
            'tank'
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
            'warlock'
        ];

        for ($index = 0; $index < count($rolesNames); $index++) {
            $roleName = $rolesNames[$index];
            $role = $this->getReference('role_' . $roleName, CharacterRole::Class);    
            for ($classIndex = 0; $classIndex < count($classesNames); $classIndex++) {
                $className = $classesNames[$classIndex];
                $class = $this->getReference('class_' . $className, CharacterClass::Class);
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
            CharacterClassFixtures::class
        ];
    }
}
