<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\FriendLinkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

final class MessageController extends AbstractController
{

    #[Route('/member/message/send/{id}', name: 'app_member_message_send', methods: ['POST'])]
    public function sendMessage(Request $request, EntityManagerInterface $entityManager, FriendLinkRepository $friendLinkRepository, User $sendTo): JsonResponse
    {
        $messageContent = $request->getPayload()->get('message');
        
        // Validate message content
        if (empty($messageContent)) {
            return new JsonResponse(['error' => 'Message cannot be empty'], 400);
        }
        
        // Check if users are friends
        if ($friendLinkRepository->hasFriend($sendTo, $this->getUser())) {
            $message = new Message();
            $message->setContent($messageContent)
                ->setSender($this->getUser())
                ->setReceiver($sendTo)
                ->setRead(false)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setIsVisible(true);

            $entityManager->persist($message);
            $entityManager->flush();
            
            return new JsonResponse(['success' => true, 'message' => 'Message sent']);
        }
        
        return new JsonResponse(['error' => 'Not authorized to send message'], 403);
    }

    #[Route('/member/message/{id}', name: 'app_member_message')]
    public function index(User $user, MessageRepository $messageRepository, FriendLinkRepository $friendLinkRepository, Request $request): Response
    {
        $sentMessages = $messageRepository->findBy(['sender' => $this->getUser(), 'receiver' => $user]);
        $receivedMessages = $messageRepository->findBy(['sender' => $user, 'receiver' => $this->getUser()]);

        if(!$friendLinkRepository->hasFriend($this->getUser(), $user))
        {
            throw new Exception('you can t message someone who is not your friend');
        }

        $friends = $friendLinkRepository->getFriendsByUser($this->getUser());

        $messages = \array_merge($receivedMessages, $sentMessages);

        // Sort messages by createdAt in ascending order (most recent last)
        usort($messages, function ($a, $b) {
            return $a->getCreatedAt() <=> $b->getCreatedAt();
        });

        return $this->render('member/views/message.html.twig', [
            'messagingWith' => $user,
            'friends' => $friends,
            'messages' => $messages
        ]);
    }

    //todo replace this crappy pooling route by turbo...
    #[Route('/member/message/update/{id}', name: 'app_member_message_update', methods: ['GET'])]
    public function updateMessages(Request $request, MessageRepository $messageRepository, FriendLinkRepository $friendLinkRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, User $chattingWith): JsonResponse
    {
        if(!$friendLinkRepository->hasFriend($this->getUser(), $user))
        {
            return new JsonResponse(['error' => 'Not authorized to fetch messages'], 403);
        }        
        
        $mostRecent = $request->query()->get('mostRecentMessageDate');

        if($mostRecent)
        {
            $mostRecent = \DateTime::createFromFormat($mostRecent);
            if (!$mostRecent instanceof \DateTime)
                return new JsonResponse(['error' => 'invalid input '.$mostRecent]);
            $messages = $repository->findBy($this->getUser(), $chattingWith, $mostRecent);

            return new JsonResponse(['success' => true, 'messages' => $serializer->serialize($messages, 'json')]);
        }
        return new JsonResponse(['error' => 'missing input mostRecentMessageDate']);
    }    
}
