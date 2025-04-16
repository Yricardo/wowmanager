<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CharacterClass;

class CharacterClassFixtures extends Fixture
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

        for ($index = 0; $index < count($classesNames); $index++) {
            $className = $classesNames[$index];
            $class = new CharacterClass();
            $class->setName($className);
            $this->addReference('class_' . $className, $class);
            $manager->persist($class);
        }

        $manager->flush();
    }
}
