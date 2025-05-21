<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AuctionRepository;
use App\Managers\AuctionManager;

final class AuctionController extends AbstractController
{
    #[Route('/auctions', name: 'app_auction_list')]
    public function index(AuctionManager $auctionManager): Response
    {
        $userAuctions = $auctionManager->getAuctionsByUser($this->getUser());
        $userBiddedOn = $auctionManager->getAuctionsByBidderUser($this->getUser());
        $userOthersVisibleAuctions = [];//todo implement
        return $this->render('member/auction/auction_list.html.twig', [
            'userAuctions' => $userAuctions,
            'biddedOn' => $userBiddedOn,
            'visibleAuctions' => $userOthersVisibleAuctions,
        ]);
    }
}
