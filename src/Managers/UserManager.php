<?php

namespace App\Managers;

use App\Repository\FriendLinkRepository;
use App\Repository\UserRepository;
use App\Managers\SettingManager; 
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserManager
{

    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private SettingManager $settingManager,
        private UserPasswordHasherInterface $pwdHasher,
        private EntityManagerInterface $em
    ) {
    }    
    
    /**
     * Add member user with basic permissions
     * 
     * @param string $username Username for the new member
     * @param string $password Password for the new member
     * @return User The created member user
     * @throws \Exception When user creation fails
     */    
    public function addMember(string $username, string $password, string $code): User
    {
        //todo when invitation system done, trigger verification with invitation code and burn invitation
        return $this->addUser($username, $password, ['ROLE_USER','ROLE_MEMBER']);
    }

    /**
     * Add admin user with elevated permissions, only super admin can create admins
     * 
     * @param string $username Username for the new admin
     * @param string $password Password for the new admin
     * @return User The created admin user
     * @throws \Exception When user creation fails
     */    
    public function addAdmin(string $username, string $password): User 
    {
        return $this->addUser($username, $password, ['ROLE_USER','ROLE_ADMIN','ROLE_MEMBER']);        
    }
    
    /**
     * Add super admin user - only one allowed
     * 
     * @param string $username Username for super admin
     * @param string $password Password for super admin
     * @return User The created super admin user
     * @throws \Exception When super admin already exists
     */    
    public function addSuperAdmin(string $username, string $password): User
    {
        if($this->userRepository->findUsersByRole('ROLE_SUPER_ADMIN'))
        {
            throw new \Exception('only one super admin is allowed in wowmanager');
        }
        //todo implement being careful, one super admin possible only
        return $this->addUser($username, $password, ['ROLE_USER','ROLE_SUPER_ADMIN','ROLE_ADMIN','ROLE_MEMBER']);
    }
    
    /**
     * Add user with specified roles and generate settings
     * 
     * @param string $username Username for the new user
     * @param string $password Plain text password to be hashed
     * @param array $roles Array of roles to assign
     * @return User The created and persisted user
     * @throws \Exception When user creation fails
     */
    private function addUser(string $username, string $password, array $roles): User
    {
        // Hash the password (based on the security.yaml config for the $user class)
        $user = new User();
        $hashedPassword = $this->pwdHasher->hashPassword(
            $user,
            $password
        );        $user->setUsername($username)
            ->setPassword($hashedPassword)
            ->setRoles($roles)
            ->setTrustScore(\in_array('ROLE_ADMIN', $roles) ? 100 : 50)
            ->setCreatedAt(new \DateTimeImmutable());
        
        // CRITICAL: Persist user FIRST before generating settings
        $this->em->persist($user);
        $this->em->flush();
        
        // Now generate settings for the persisted user
        //$this->settingManager->generateSettingsForUser($user);
        
        return $user;
    }
}