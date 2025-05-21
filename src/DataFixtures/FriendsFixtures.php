<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Guild;
use App\Entity\Character;
use App\Entity\Server;
use App\Entity\User;

class FriendsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Retrieve all users from the references set in UserFixtures
        $users = [];
        for ($i = 1; $i <= 20; $i++) {
			$user = $this->getReference('f_user' . $i, User::class);
        }

        // Create random friend links between users
        foreach ($users as $user1) {
            $friendCount = rand(2, 5); // Each user will have 2 to 5 friends
            $friends = array_rand($users, $friendCount);

            foreach ((array) $friends as $friendIndex) {
                $user2 = $users[$friendIndex];

                // Avoid self-referencing and duplicate links
                if ($user1 !== $user2) {
                    $user1->addFriend($user2);
                    $user2->addFriend($user1);
                }
            }
        }

        // Persist changes
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
