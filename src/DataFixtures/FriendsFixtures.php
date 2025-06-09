<?php

namespace App\DataFixtures;

use App\Entity\FriendLink;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FriendsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Retrieve all users from the references set in UserFixtures
        $users = [];
        for ($i = 1; $i <= 20; ++$i) {
            $users[] = $this->getReference('f_user'.$i, User::class);
        }

        // Create random friend links between users
        foreach ($users as $user1) {
            $friendCount = rand(2, 10); // Each user will have 2 to 10 friends
            $friends = array_rand($users, $friendCount);
            foreach ((array) $friends as $friendIndex) {
                $user2 = $users[$friendIndex];
                if ($user1 === $user2) {
                    continue;
                }
                $friendLink = new FriendLink()
                ->setUser1($user1)
                ->setUser2($users[$friendIndex]);
                $manager->persist($friendLink);
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
