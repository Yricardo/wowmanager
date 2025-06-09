<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Find complete conversation between two users, sorted by date.
     *
     * @return Message[]
     */
    public function findConversationBetweenUsers(User $user1, User $user2): array
    {
        return $this->createQueryBuilder('m')
            ->where('
                (m.sender = :user1 AND m.receiver = :user2) OR 
                (m.sender = :user2 AND m.receiver = :user1)
            ')
            ->andWhere('m.isVisible = true')
            ->setParameter('user1', $user1)
            ->setParameter('user2', $user2)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find new messages between users since a specific date.
     *
     * @return Message[]
     */
    public function findNewMessagesBetweenUsers(User $user1, User $user2, \DateTime $since): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.createdAt > :since')
            ->andWhere('
                (m.sender = :user2 AND m.receiver = :user1)
            ')
            ->andWhere('m.isVisible = true')
            ->setParameter('since', $since)
            ->setParameter('user1', $user1)
            ->setParameter('user2', $user2)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Count unread messages for a user.
     */
    public function countUnreadMessagesForUser(User $user): int
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->where('m.receiver = :user')
            ->andWhere('m.isRead = false')
            ->andWhere('m.isVisible = true')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find users with whom the given user has conversations.
     *
     * @return User[]
     */
    public function findConversationPartners(User $user): array
    {
        $qb = $this->createQueryBuilder('m');

        return $qb
            ->select('DISTINCT IDENTITY(m.sender) as sender_id, IDENTITY(m.receiver) as receiver_id')
            ->where('m.sender = :user OR m.receiver = :user')
            ->andWhere('m.isVisible = true')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
