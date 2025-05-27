<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\FriendLinkRepository;

final class FriendController extends AbstractController
{
    #[Route('member/friend/list', name: 'app_member_friend_list')]
    public function index(FriendLinkRepository $repository): Response
    {
        $friends = $repository->getFriendsByUser($this->getUser());
        return $this->render('member/views/friend_list.html.twig', ['friends' => $friends]);
    }

    #[Route('member/friend/add', name: 'app_member_add_friend')]
    public function addFriend(): Response
    {
        return $this->render('member/views/friend_list.html.twig', []);//todo implement
    }
}
