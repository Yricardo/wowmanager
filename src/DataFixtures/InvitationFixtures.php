<?php

namespace App\DataFixtures;

use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InvitationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Retrieve users from UserFixtures using the reference names 'f_user1', 'f_user2', etc.
        $users = [];
        for ($i = 1; $i <= 20; ++$i) {
            $users[] = $this->getReference('f_user'.$i, User::class);
        }

        // Create invitations: each of the first 10 users invites the next user
        for ($i = 0; $i < 10; ++$i) {
            $invitation = new Invitation();

            // Setters according to Invitation entity
            $invitation = (new Invitation())
                ->setStatus(Invitation::STATUS_PENDING)
                ->setInvitedBy($users[$i])
                ->setSecretTag(bin2hex(random_bytes(8)))
                ->setEmail('invited'.($i + 1).'@example.com')
                ->setCreatedAt(new \DateTimeImmutable())
                ->setTimeToLive(2)
                ->setForRole(User::ROLE_MEMBER);

            $manager->persist($invitation);

            // Store references for possible use in other fixtures
            $this->addReference('invitation_'.($i + 1), $invitation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
