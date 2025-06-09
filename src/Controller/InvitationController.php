<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\User;
use App\Managers\InvitationManager;
use App\Repository\InvitationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InvitationController extends AbstractController
{
    #[Route('member/invitation/list', name: 'app_member_invitation_list')]
    public function invitationList(InvitationRepository $invitationRepository): Response
    {
        $pendingInvitations = $invitationRepository->findBy(['invitedBy' => $this->getUser(), 'status' => Invitation::STATUS_PENDING]);
        $expiredInvitations = $invitationRepository->findBy(['invitedBy' => $this->getUser(), 'status' => Invitation::STATUS_EXPIRED]);
        $invitations = \array_merge($pendingInvitations, $expiredInvitations);

        return $this->render('member/views/invitation_list.html.twig', [
            'invitations' => $invitations,
            'totalInvitations' => count($invitations),
            'totalPending' => count($pendingInvitations),
            'totalExpired' => count($expiredInvitations),
        ]);
    }

    // create-invitation.htmm
    #[Route('member/invitation/create', name: 'app_member_create_invitation', methods: ['GET', 'POST']), ]
    public function createInvitation(Request $request, LoggerInterface $logger, InvitationManager $invitationManager, InvitationRepository $repository): Response
    {
        try {
            if ($request->isMethod('POST')) {
                $email = $request->getPayload()->get('email');
                $personnalMessage = $request->getPayload()->get('message');
                if (!$email || false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->addFlash('error', 'Email is either empty or invalid.');

                    return $this->redirectToRoute('app_member_create_invitation');
                }

                if ($repository->findOneBy(['email' => $email])) {
                    $this->addFlash('error', 'An invitation with this email already exists.'); // todo handle display in view

                    return $this->redirectToRoute('app_member_create_invitation');
                }

                $invitationManager->createInvitation(
                    $this->getUser(),
                    $email,
                    User::ROLE_MEMBER,
                    2, // Time to live in days
                    $personnalMessage
                );

                $this->addFlash('success', 'Invitation created successfully!');

                return $this->redirectToRoute('app_member_invitation_list');
            }
        } catch (\Throwable $e) {
            throw $e; // todo remove
            $this->addFlash('error', 'An error occurred while creating the invitation');
            $logger->error('Invitation creation failed', [
                'error' => $e->getMessage(),
                'user' => $this->getUser()->getId(),
            ]);
        }

        return $this->render('member/views/invitation_create.html.twig', []);
    }
}
