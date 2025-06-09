<?php

namespace App\Manager;
use App\Repository\InvitationRepository;
use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Psr\Log\LoggerInterface;
use App\Manager\UserManager;
use App\Manager\FriendManager;

use Exception;

class InvitationManager
{
    public function __construct(
        private InvitationRepository $invitationRepository,
        private EntityManagerInterface $entityManager,
        private UserManager $userManager,
        private FriendManager $friendManager,
        private LoggerInterface $logger
    ) {}

    //todo use in invitation controller and adapt
    /**
     * Create a new invitation
     */
    public function createInvitation(User $invitedBy, string $email, string $forRole, int $timeToLive, ?string $personalMessage = null): Invitation
    {
        if($this->getInvitationByCode($email)) {
            throw new Exception('An invitation with this email already exists.');
        }
        $invitation = new Invitation();
        $invitation->setInvitedBy($invitedBy)
                   ->setEmail($email)
                   ->setForRole($forRole)
                   ->setTimeToLive($timeToLive)
                   ->setCreatedAt(new \DateTimeImmutable())
                   ->setStatus(Invitation::STATUS_PENDING)
                   ->setSecretTag(bin2hex(random_bytes(16)));
        if($personalMessage) {
            $invitation->setPersonnalMessage($personalMessage);
        }

        $this->entityManager->persist($invitation);
        $this->entityManager->flush();

        return $invitation;
    }

    public function getInvitationByCode(string $invitationCode): ?Invitation
    {
        $invitation = $this->invitationRepository->findOneBy([
            'secretTag' => $invitationCode,
            'status' => Invitation::STATUS_PENDING
        ]);

        if (!$invitation) {
            $this->logger->error('Invalid or expired invitation code', ['invitationCode' => $invitationCode]);
            return null;
        }

        return $invitation;
    }

    public function transformInvitationToMember(Invitation $invitation, string $username, string $password): User
    {
        if ($invitation->getStatus() !== Invitation::STATUS_PENDING)
        {
            throw new AccessDeniedException('Invitation is not valid for registration.');
        }

        if($invitation->getForRole() === User::ROLE_ADMIN)
        {
            throw new Exception('You cannot create a member from an admin invitation.');
        }

        // Create a new user from the invitation
        $member = $this->userManager->addMember(
            $username,
            $password,
            $invitation->getSecretTag()
        );

        if($invitedBy = $invitation->getInvitedBy())
        {
            $this->friendManager->addFriend($invitedBy, $member);
        }

        $this->burnInvitation($invitation);
        return $member;
    }

    private function burnInvitation(Invitation $invitation): void
    {
        $this->entityManager->remove($invitation);
        $this->entityManager->flush();
    }
}
