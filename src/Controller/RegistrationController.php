<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Invitation;
use App\Form\RegistrationForm;
use App\Managers\InvitationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\InvitationRepository;
use App\Managers\UserManager;
use Psr\Log\LoggerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register/member/{invitationCode}', name: 'app_register_member')]
    public function registerMember(Request $request, UserManager $userManager, InvitationManager $invitationManager, InvitationRepository $invitationRepository ,LoggerInterface $logger, string $invitationCode): Response
    {
        //todo refactor into a manager what can be
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        $invitation = $invitationRepository->findOneBy([
            'secretTag' => $invitationCode, 
            'status' => Invitation::STATUS_PENDING,
            'forRole' => User::ROLE_MEMBER
        ]);

        if (!$invitation) {
            $this->addFlash('error', 'Invalid or expired invitation code.');
            $logger->error('Some one tried to register with invalid or expired invitation code', ['invitationCode' => $invitationCode]);
            return $this->redirectToRoute('app_login');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                //we do not handle user creation persistence here, todo : symplify form, not taking user entity into account
                $user = $form->getData();
                $invitationManager->transformInvitationToMember($invitation, $user->getUsername(), $user->getPassword());
                $this->addFlash('success', 'Registration complete. you can now log in.');//todo handle display in login view
                return $this->redirectToRoute('app_login');
            } 
            catch (\Throwable $e) {
                throw $e; //todo remove 
                $logger->error('Registration failed', [
                    'error' => $e->getMessage(),
                    'invitationCode' => $invitationCode,
                    'username' => $form->get('username')->getData()
                ]);
                $this->addFlash('error', 'Registration failed');//todo handle display in login view
                return $this->redirectToRoute('app_login', ['invitationCode' => $invitationCode]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/register/admin/{invitationCode}', name: 'app_register_admin')]
    public function registerAdmin(Request $request, UserManager $userManager, InvitationRepository $invitationRepository ,LoggerInterface $logger, string $invitationCode): Response
    {
        //todo refactor into a manager 
        $invitation = $invitationRepository->findOneBy([
            'secretTag' => $invitationCode, 
            'status' => Invitation::STATUS_PENDING,
            'forRole' => User::ROLE_MEMBER
        ]);

        if (!$invitation) {
            $this->addFlash('error', 'Invalid or expired invitation code.');
            $logger->error('Some one tried to register with invalid or expired invitation code', ['invitationCode' => $invitationCode]);
            return $this->redirectToRoute('app_login');
        }

        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $datas = $form->getData();
                $username = $datas['username'];
                $password = $datas['plainPassword'];
                $userManager->addAdmin($username, $password, $invitationCode);
                return $this->redirectToRoute('app_login');
            } 
            catch (\Throwable $e) {
                $logger->error('Registration failed', [
                    'error' => $e->getMessage(),
                    'invitationCode' => $invitationCode,
                    'username' => $form->get('username')->getData()
                ]);
                $this->addFlash('error', 'Registration failed: ' . $e->getMessage());
                return $this->redirectToRoute('app_register_member', ['invitationCode' => $invitationCode]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
