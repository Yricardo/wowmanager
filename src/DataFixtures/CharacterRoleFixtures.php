<?php

namespace App\DataFixtures;

use App\Entity\CharacterRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CharacterRoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rolesNames = [
            'dps',
            'heal',
            'tank',
        ];

        for ($index = 0; $index < count($rolesNames); ++$index) {
            $roleName = $rolesNames[$index];
            $role = new CharacterRole();
            $role->setName($roleName);
            $role->setImgPath('images/roles/'.$roleName.'.png');
            $this->addReference('role_'.$roleName, $role);
            $manager->persist($role);
        }

        $manager->flush();
    }
}
