<?php

namespace App\Repository;

use App\Entity\FriendLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<FriendLink>
 */
class FriendLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FriendLink::class);
    }

    public function hasFriend(User $friend, User $with): bool
    {
        $qb = $this->createQueryBuilder('f'); 
        return (bool) \count(($qb->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('f.user1', ':u1'),
                    $qb->expr()->eq('f.user2', ':u2')
                )
            )
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('f.user1', ':uu1'),
                    $qb->expr()->eq('f.user2', ':uu2')
                )                
            )
            ->setParameter('u1', $friend->getId())
            ->setParameter('u2', $friend->getId())
            ->setParameter('uu1', $with->getId())
            ->setParameter('uu2', $with->getId())           
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()));    
    }

    public function getFriendsByUser(User $user) : array
    {
        $qb = $this->createQueryBuilder('f'); 
        $results =  
            $qb->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('f.user1', $user->getId()),
                    $qb->expr()->eq('f.user2', $user->getId())
                )
            )
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();  
        return \array_map(function ($friendLink) use ($user) {
            return $friendLink->getUser1()->getId() === $user->getId() ? $friendLink->getUser2() : $friendLink->getUser1();
        }, $results);
    }

    //    /**
    //     * @return FriendLink[] Returns an array of FriendLink objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FriendLink
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
