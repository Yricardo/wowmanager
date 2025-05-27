<?php

namespace App\Controller;

use App\Entity\User;
use App\Managers\MessageManager;
use App\Repository\FriendLinkRepository;
use App\Exception\MessageException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

#[Route('/member/message')]
final class MessageController extends AbstractController
{
    public function __construct(
        private MessageManager $messageManager,
        private FriendLinkRepository $friendLinkRepository,
        private SerializerInterface $serializer,
        private LoggerInterface $logger
    ) {}

    #[Route('/send/{id}', name: 'app_member_message_send', methods: ['POST'])]
    public function sendMessage(Request $request, User $receiver): JsonResponse
    {
        try {
            $content = $request->getPayload()->get('message');
            
            $message = $this->messageManager->sendMessage(
                $this->getUser(), 
                $receiver, 
                $content
            );
            
            return new JsonResponse([
                'success' => true, 
                'message' => 'Message sent successfully',
                'messageId' => $message->getId()
            ]);
            
        } catch (MessageException $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], $e->getHttpStatusCode());
            
        } catch (\Exception $e) {
            $this->logger->error('Failed to send message', [
                'error' => $e->getMessage(),
                'sender' => $this->getUser()->getId(),
                'receiver' => $receiver->getId()
            ]);
            
            return new JsonResponse([
                'error' => 'Failed to send message'
            ], 500);
        }
    }

    #[Route('/{id}', name: 'app_member_message')]
    public function conversation(User $user): Response
    {
        try {
            $messages = $this->messageManager->getConversation($this->getUser(), $user);
            $friends = $this->friendLinkRepository->getFriendsByUser($this->getUser());

            return $this->render('member/views/message.html.twig', [
                'messagingWith' => $user,
                'friends' => $friends,
                'messages' => $messages
            ]);
            
        } catch (MessageException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_member_dashboard'); // or appropriate route
            
        } catch (\Exception $e) {
            $this->logger->error('Failed to load conversation', [
                'error' => $e->getMessage(),
                'user1' => $this->getUser()->getId(),
                'user2' => $user->getId()
            ]);
            
            $this->addFlash('error', 'Failed to load conversation');
            return $this->redirectToRoute('app_member_dashboard');
        }
    }

    #[Route('/update/{id}', name: 'app_member_message_update', methods: ['GET'])]
    public function getNewMessages(Request $request, User $chattingWith, SerializerInterface $serializer): JsonResponse
    {
        try {
            $timestamp = $this->parseTimestamp($request->query->get('mostRecentMessageDate'));
            
            $newMessages = $this->messageManager->getNewMessages(
                $this->getUser(), 
                $chattingWith, 
                $timestamp
            );

            // Manually format to avoid circular references TODO FIX
            $messages = [];
            foreach ($newMessages as $message) {
                $messages[] = [
                    'id' => $message->getId(),
                    'content' => $message->getContent(),
                    'createdAt' => $message->getCreatedAt()->format('c'),
                    'read' => $message->isRead(),
                    'sender' => [
                        'id' => $message->getSender()->getId()
                        // Remove username to avoid circular references
                    ],
                    'receiver' => [
                        'id' => $message->getReceiver()->getId()
                        // Remove username to avoid circular references  
                    ]
                ];
            }

            return new JsonResponse([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (MessageException $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], $e->getHttpStatusCode());
            
        } catch (\Exception $e) {
            $this->logger->error('Failed to fetch new messages', [
                'error' => $e->getMessage(),
                'user1' => $this->getUser()->getId(),
                'user2' => $chattingWith->getId()
            ]);
            
            return new JsonResponse([
                'error' => 'Failed to fetch messages: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse and validate timestamp parameter
     * 
     * @throws MessageException
     */
    private function parseTimestamp(?string $timestampParam): \DateTime
    {
        if (!$timestampParam) {
            throw new MessageException(
                'Missing mostRecentMessageDate parameter', 
                MessageException::INVALID_TIMESTAMP
            );
        }

        try {
            $timestamp = (int) $timestampParam;
            $date = new \DateTime();
            $date->setTimestamp($timestamp);
            return $date;
            
        } catch (\Exception $e) {
            throw new MessageException(
                'Invalid timestamp format', 
                MessageException::INVALID_TIMESTAMP
            );
        }
    }
}