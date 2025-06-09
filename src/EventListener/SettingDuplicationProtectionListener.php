<?php

namespace App\EventListener;

use App\Entity\Setting;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Setting::class)]
class SettingDuplicationProtectionListener
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function prePersist(Setting $setting, PrePersistEventArgs $event): void
    {
        $entityManager = $event->getObjectManager();
        $user = $setting->getUser();
        $settingName = $setting->getName();
        $isGlobal = $setting->isGlobal();

        $qb = $entityManager->createQueryBuilder();
        $qb->select('COUNT(s.id)')
           ->from(Setting::class, 's')
           ->where('s.name = :name')
           ->andWhere('s.isGlobal = :isGlobal')
           ->setParameter('name', $settingName)
           ->setParameter('isGlobal', $isGlobal);

        if (!$isGlobal && null !== $user) {
            $qb->andWhere('s.user = :user')
               ->setParameter('user', $user);
        }

        $existingCount = $qb->getQuery()->getSingleScalarResult();

        if ($existingCount > 0) {
            $logContext = [
                'setting_name' => $settingName,
                'is_global' => $isGlobal,
                'user_id' => $user?->getId(),
                'username' => $user?->getUsername(),
            ];

            if ($isGlobal) {
                $this->logger->critical('SECURITY ALERT: Duplicate global setting attempt prevented!', $logContext);
                throw new \RuntimeException("Setting duplication protection: Cannot create duplicate global setting '{$settingName}'.");
            } else {
                $this->logger->warning('Duplicate user setting attempt prevented', $logContext);
                throw new \RuntimeException("Setting duplication protection: Cannot create duplicate setting '{$settingName}' for user '{$user?->getUsername()}'.");
            }
        }
    }
}
