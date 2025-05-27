<?php

namespace App\Managers;

use App\Repository\FriendLinkRepository;
use App\Repository\UserRepository;

class FriendManager
{

    public function __construct(
        private FriendLinkRepository $friendLinkRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function addFriend(User $user, User $friend): array
    {
        if ($this->friendLinkRepository->hasFriend($friend, $with))
        {
            $friendLink = new FriendLink();
            $friendLink->setUser1($user);
            $friendLink->setUser2($user);
            $entityManager->persist($friendLink);
            $entityManager->flush();
        }
    }
}