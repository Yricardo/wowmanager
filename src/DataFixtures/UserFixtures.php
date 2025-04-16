<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Create a new User Entity with admin role set
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setCreatedAt(new \DateTimeImmutable());
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        // Create 20 User Entities with user and contributor role set
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setUsername('f_user' . $i);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'f_user' . $i));
            $user->setRoles(['ROLE_USER', 'ROLE_CONTRIBUTOR']);
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            $this->addReference('f_user' . $i, $user);
        }

        // Create 20 more entities with user fundator set
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setUsername('s_user' . $i);
            $user->setPassword($this->passwordHasher->hashPassword($user, 's_user' . $i));
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            $this->addReference('s_user' . $i, $user);
        }

        $manager->flush();
    }
}
