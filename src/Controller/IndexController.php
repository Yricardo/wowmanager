<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    //require logged in user
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        // If user has role 'ROLE_ADMIN', redirect to admin page
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin');
        }
        // If user has role 'ROLE_USER', redirect to user page
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_member');
        }

        throw $this->createAccessDeniedException('Unauthorized user');
    }
}
