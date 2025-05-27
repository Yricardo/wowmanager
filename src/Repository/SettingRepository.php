<?php

namespace App\Repository;

use App\Entity\Setting;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Setting>
 */
class SettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    public function updateSettingValueByName(User $user, string $name, mixed $value)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->update('App\\Entity\\Setting', 's')
        ->where('s.name = :name AND s.user = :userId')
        ->set('s.value', ':value')
        ->setParameter('name', $name)
        ->setParameter('value', $value)
        ->setParameter('userId', $user->getId())
        ->getQuery();
        $qb->getQuery()->execute();
    }

    //    /**
    //     * @return Setting[] Returns an array of Setting objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
}
