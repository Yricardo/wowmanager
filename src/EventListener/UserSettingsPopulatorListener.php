<?php 

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use App\Managers\SettingManager;
use Doctrine\ORM\Mapping\PostPersist;

/**
 * Populates user settings after a user is updated.
 * This listener checks if the user already has settings populated,
 * and if not, it generates default settings for the user.
 */
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: User::class)]
class UserSettingsPopulatorListener
{

    public function __construct(
        private SettingManager $settingManager,
    ){}


    public function postPersist(User $user, PostPersistEventArgs $event): void
    {
        try {
            if($this->settingManager->getSettings($user) !== []) {
                return; // User settings already populated, no need to check further
            }
            $this->settingManager->generateSettingsForUser($user);
        }catch (\Exception $e) {
            throw new \RuntimeException('Error populating user settings: ' . $e->getMessage());
        }
    }
}
