<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;

/**
 * Protects super admin users from being created or deleted after deployment
 * Only allows one super admin to exist and prevents unauthorized modifications.
 */
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: User::class)]
class SuperAdminProtectionListener
{
    /**
     * Prevent creation of additional super admin users after deployment.
     */
    public function prePersist(User $user, PrePersistEventArgs $event): void
    {
        if (!$this->hasSuperAdminRole($user)) {
            return;
        }

        $entityManager = $event->getObjectManager();
        $repository = $entityManager->getRepository(User::class);

        // Check if a super admin already exists
        $existingSuperAdmin = $repository->findOneBy([]);
        if ($existingSuperAdmin) {
            $qb = $entityManager->createQueryBuilder();
            $qb->select('COUNT(u.id)')
               ->from(User::class, 'u')
               ->where('u.roles LIKE :role')
               ->setParameter('role', '%ROLE_SUPER_ADMIN%');

            $count = $qb->getQuery()->getSingleScalarResult();

            if ($count > 0) {
                throw new \RuntimeException('Super admin protection: Only one super admin is allowed. Cannot create additional super admin users after deployment.');
            }
        }
    }

    /**
     * Prevent deletion of super admin users after deployment.
     */
    public function preRemove(User $user, PreRemoveEventArgs $event): void
    {
        if ($this->hasSuperAdminRole($user)) {
            throw new \RuntimeException('Super admin protection: Cannot delete super admin user. This action is forbidden for security reasons.');
        }
    }

    /**
     * Check if user has super admin role.
     */
    private function hasSuperAdminRole(User $user): bool
    {
        return \in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true);
    }
}
