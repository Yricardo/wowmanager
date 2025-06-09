<?php

namespace App\Managers;

use App\Entity\FriendLink;
use App\Entity\User;
use App\Repository\FriendLinkRepository;
use Doctrine\ORM\EntityManagerInterface;

class FriendManager
{
    public function __construct(
        private FriendLinkRepository $friendLinkRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function addFriend(User $with, User $friend): void
    {
        if (!$this->friendLinkRepository->hasFriend($friend, $with)) {
            $friendLink = new FriendLink();
            $friendLink->setUser1($with);
            $friendLink->setUser2($friend);
            $this->entityManager->persist($friendLink);
            $this->entityManager->flush();
        }
    }
}
