<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Retrieve users from UserFixtures using the reference names 'f_user1', 'f_user2', etc.
        $users = [];
        for ($i = 1; $i <= 20; ++$i) {
            $users[] = $this->getReference('f_user'.$i, User::class);
        }

        // Create invitations: each of the first 10 users invites the next user
        foreach ($users as $user) {
            if ('f_user1' === $user->getUsername()) {
                continue;
            }
            $message = new Message()
            ->setContent('blah blah u')
            ->setSender($user)
            ->setReceiver($users[0])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setRead(false)
            ->setIsVisible(true);
            $manager->persist($message);

            $message = new Message()
            ->setContent('efasqfqfsaf u too')
            ->setSender($users[0])
            ->setReceiver($user)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setRead(false)
            ->setIsVisible(true);
            $manager->persist($message);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            FriendsFixtures::class,
        ];
    }
}
