<?php

namespace App\Manager;

use App\Repository\FriendLinkRepository;
use App\Repository\UserRepository;
use App\Entity\FriendLink;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;


class FriendManager
{

    public function __construct(
        private FriendLinkRepository $friendLinkRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function addFriend(User $with, User $friend): void
    {
        if (!$this->friendLinkRepository->hasFriend($friend, $with))
        {
            $friendLink = new FriendLink();
            $friendLink->setUser1($with);
            $friendLink->setUser2($friend);
            $this->entityManager->persist($friendLink);
            $this->entityManager->flush();
        }
    }
}
