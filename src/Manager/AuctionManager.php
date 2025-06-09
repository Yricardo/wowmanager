<?php

namespace App\Manager;

use App\Entity\Auction;
use App\Entity\Price;
use App\Entity\Character;
use App\Entity\Item;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuctionRepository;
use App\Repository\AuctionBidRepository;

class AuctionManager
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AuctionRepository $auctionRepository,
        private AuctionBidRepository $auctionBidRepository
    ) {
    }

    public function createAuction(User $seller,Item $item,int $quantity,float $price,float $buyoutPrice)
    {
        // Create a new auction entity
        $auction = new Auction();
        $auction->setSeller($seller);
        $auction->setItem($item);
        $auction->setQuantity($quantity);
        $auction->setPrice($price);
        $auction->setBuyoutPrice($buyoutPrice);
        $auction->setCreationDate(new \DateTime());

        // Persist the auction entity
        $this->entityManager->persist($auction);
        $this->entityManager->flush();

        return $auction;
    }

    public function getAuctionById(int $id)
    {
        return $this->auctionRepository->find($id);
    }

    public function getAuctionsByUser(User $user)
    {
        $auctions = [];
        foreach ($user->getCharacters() as $character) {
            foreach ($this->auctionRepository->findBy(['seller' => $character]) as $auction) {
                $auctions[] = $auction;
            }
        }
        return $auctions;
    }

    public function getAuctionsBySeller(Character $seller)
    {
        return $this->auctionRepository->findBy(['seller' => $seller]);
    }

    public function getAuctionsByItem(Item $item)
    {
        return $this->auctionRepository->findBy(['item' => $item]);
    }

    public function getAuctionsByPriceRange(float $minPrice, float $maxPrice)
    {
        return $this->auctionRepository->findByPriceBetween($minPrice, $maxPrice);
    }


    public function deleteAuction(Auction $auction)
    {
        $this->entityManager->remove($auction);
        $this->entityManager->flush();
    }

    public function updateAuctionItemQuantity(Auction $auction, int $newQuantity)
    {
        $auction->setQuantity($newQuantity);
        $this->entityManager->flush();
    }

    public function getLastBidByAuctionForUser(Auction $auction, User $user)
    {
        //todo implement
    }

    public function getLastBidsByUser(User $user)
    {
        $bids = [];
        foreach ($user->getCharacters() as $character) {
            $bids = array_merge($bids, $this->auctionBidRepository->findBy(['bidder' => $character]));
        }
        return $bids;
    }

    public function getAuctionsByBidderUser(User $user): array
    {
        $biddedOn = [];
        $auctions = [];

        foreach ($user->getCharacters() as $character) {
            $biddedOn = array_merge($biddedOn, $this->auctionBidRepository->findBy(['bidder' => $character]));
        }

        foreach ($biddedOn as $bid) {
            $auctions = \array_unique(array_merge($bid->getAuction(), $auctions));

        }

        return $biddedOn;
    }
}
