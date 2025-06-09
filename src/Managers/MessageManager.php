<?php

namespace App\Managers;

use App\Entity\Message;
use App\Entity\User;
use App\Exception\MessageException;
use App\Repository\FriendLinkRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;

class MessageManager
{
    public function __construct(
        private MessageRepository $messageRepository,
        private FriendLinkRepository $friendLinkRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Send a message between users.
     *
     * @throws MessageException
     */
    public function sendMessage(User $sender, User $receiver, string $content): Message
    {
        $this->validateFriendship($sender, $receiver);
        $this->validateMessageContent($content);

        $message = new Message();
        $message->setContent(trim($content))
            ->setSender($sender)
            ->setReceiver($receiver)
            ->setRead(false)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setIsVisible(true);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }

    /**
     * Get conversation between two users.
     *
     * @return Message[]
     *
     * @throws MessageException
     */
    public function getConversation(User $user1, User $user2): array
    {
        $this->validateFriendship($user1, $user2);

        return $this->messageRepository->findConversationBetweenUsers($user1, $user2);
    }

    /**
     * Get new messages since a specific timestamp.
     *
     * @return Message[]
     *
     * @throws MessageException
     */
    public function getNewMessages(User $currentUser, User $otherUser, \DateTime $since): array
    {
        $this->validateFriendship($currentUser, $otherUser);

        $newMessages = $this->messageRepository->findNewMessagesBetweenUsers(
            $currentUser,
            $otherUser,
            $since
        );

        // Mark received messages as read
        $this->markMessagesAsRead($newMessages, $currentUser);

        return $newMessages;
    }

    /**
     * Mark messages as read for a specific user.
     */
    private function markMessagesAsRead(array $messages, User $reader): void
    {
        $hasChanges = false;

        foreach ($messages as $message) {
            if ($message->getReceiver()->getId() === $reader->getId() && !$message->isRead()) {
                $message->setRead(true);
                $hasChanges = true;
            }
        }

        if ($hasChanges) {
            $this->entityManager->flush();
        }
    }

    /**
     * Validate that users are friends.
     *
     * @throws MessageException
     */
    private function validateFriendship(User $user1, User $user2): void
    {
        if (!$this->friendLinkRepository->hasFriend($user1, $user2)) {
            throw new MessageException('Users are not friends', MessageException::NOT_FRIENDS);
        }
    }

    /**
     * Validate message content.
     *
     * @throws MessageException
     */
    private function validateMessageContent(string $content): void
    {
        $trimmed = trim($content);

        if (empty($trimmed)) {
            throw new MessageException('Message cannot be empty', MessageException::EMPTY_MESSAGE);
        }

        if (strlen($trimmed) > 1000) { // Add reasonable limit
            throw new MessageException('Message too long', MessageException::MESSAGE_TOO_LONG);
        }
    }
}
